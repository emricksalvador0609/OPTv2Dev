<?php

namespace App\Services;

use App\Models\OPTv2Allocated;
use App\Models\OPTv2Projectiond;
use App\Models\OPTv2Projectionh;
use App\Models\OPTv2User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Rows behind the Stock Allocation Summary datatable
 * (GET /datatable_reports_stockallocationsummary).
 *
 * The previous implementation built the response inside the controller and ran
 * two correlated subqueries for every returned row:
 *
 *   (SELECT TOP 1 DESCRIPTION FROM OPTV2PROJECTIOND t2 WHERE t2.EAN11 = t1.EAN11)
 *   (SELECT TOP 1 PERNRNAME FROM OPTV2PROJECTIONH t3 WHERE t3.PERNR = t1.PERNR ...)
 *
 * Neither column is indexed, so each row cost two full table scans. A projection
 * with 3,400 allocated rows meant ~6,800 scans and took 24 s on live. Both
 * lookups are now fetched in bulk (one query per source, chunked so the SQL
 * Server parameter limit is never reached) and matched in PHP.
 *
 * Ordered quantity still comes from ProjectionOmsOrderedService, keyed on the
 * projection batch (prd.ZSD_OMS_SSY.BATCHID = BASEDOCNUM) and its school opening,
 * never on the OMS creation year.
 *
 * The rendered output is unchanged: same keys, same HTML, same ordering.
 */
final class StockAllocationSummaryService
{
    /**
     * Values bound per WHERE IN. SQL Server rejects more than 2,100 parameters
     * in one statement, so lookups stay well below that.
     */
    private const LOOKUP_CHUNK = 500;

    public function __construct(private string $omsConnection = 'OMSL')
    {
    }

    /**
     * @param  string      $basedocnum projection id (BASEDOCNUM / PID)
     * @param  string|null $pernr      '1' or null = every AE
     * @param  string|null $division   '1' or null = every division
     * @return array<int, array<string, mixed>>|array{num: int}
     */
    public function response(string $basedocnum, ?string $pernr, ?string $division): array
    {
        $query = OPTv2Allocated::from('OPTV2ALLOCATED as t1')
            ->where('BASEDOCNUM', $basedocnum)
            ->where('t1.PROJECTION', '<>', '0')
            ->orderBy('EAN11', 'DESC');

        $breakdown = OPTv2Projectiond::from('OPTV2PROJECTIOND as t1')
            ->leftjoin('OPTV2PROJECTIONH as t2', 't1.DOCNUM', '=', 't2.DOCNUM')
            ->selectRaw('
                t1.EAN11,
                t2.BSA,
                t1.PERNR,
                t1.BRANCHWHOUSE,
                MAX(t2.PERNRNAME) as PERNRNAME,
                SUM(CAST(t1.QTY AS INT)) as TOTALPROJTN
            ')
            ->where('t1.BASEDOCNUM', $basedocnum)
            ->whereNotNull('t1.APPROVED')
            ->groupBy('t1.EAN11', 't2.BSA', 't1.PERNR', 't1.BRANCHWHOUSE');

        $allowedPernrs = null;

        if ($division !== null && $division !== '1') {
            $allowedPernrs = $this->pernrsForDivision($division);

            if (empty($allowedPernrs)) {
                return ['num' => 0];
            }
        }

        if ($pernr !== null && $pernr !== '1') {
            $query->where('PERNR', $pernr);
            $breakdown->where('t1.PERNR', $pernr);
        }

        $allocated = $query->get();
        $breakdownRows = $breakdown->get();

        // The division restriction is applied in PHP. Passing a few thousand
        // PERNRs as WHERE IN bindings would either hit the 2,100-parameter limit
        // or force a non-sargable scan of OPTV2USER, and the rows for one
        // projection are already in memory at this point.
        if ($allowedPernrs !== null) {
            $allowed = array_fill_keys($allowedPernrs, true);

            $allocated = $allocated
                ->filter(function ($row) use ($allowed) {
                    return isset($allowed[$row->PERNR]);
                })
                ->values();

            $breakdownRows = $breakdownRows
                ->filter(function ($row) use ($allowed) {
                    return isset($allowed[$row->PERNR]);
                })
                ->values();
        }

        if ($allocated->isEmpty()) {
            return ['num' => 0];
        }

        $breakdownGrouped = $breakdownRows->groupBy(function ($item) {
            return $item->EAN11 . '-' . $item->BSA . '-' . trim((string) $item->PERNR);
        });

        $descriptions = $this->descriptionMap($allocated->pluck('EAN11')->filter()->unique()->values()->all());
        $names = $this->nameMap($allocated->pluck('PERNR')->filter()->unique()->values()->all());

        $orderedMap = (new ProjectionOmsOrderedService($this->omsConnection))
            ->orderedMap($basedocnum, filter_user_list_in_projection()->pluck('PERNR')->toArray());

        return $this->buildResponse($allocated, $breakdownGrouped, $descriptions, $names, $orderedMap);
    }

    /**
     * @param  Collection                          $allocated
     * @param  Collection                          $breakdownGrouped
     * @param  array<string, string|null>          $descriptions
     * @param  array<string, string|null>          $names
     * @param  array<string, array<string, float>> $orderedMap
     * @return array<int, array<string, mixed>>
     */
    private function buildResponse(
        Collection $allocated,
        Collection $breakdownGrouped,
        array $descriptions,
        array $names,
        array $orderedMap
    ): array {
        $response = [];
        $num = 0;

        foreach ($allocated as $r) {
            $num++;

            $isbn = $r->EAN11;
            $pernr = $r->PERNR;
            $pernrname = $names[$r->PERNR] ?? null;
            $alloctype = $r->ALLOCTYPE;
            $description = $descriptions[$r->EAN11] ?? null;
            $allocation = $r->QTY;
            $projection = $r->PROJECTION;
            $ordered = $orderedMap[$isbn][trim((string) $pernr)] ?? 0;
            $orderedDisplay = number_format($ordered);

            $descriptionDisplay = '<span title="' . $description . '" class="line-clamp-1">' . $description . '</span>';

            $bsa = $alloctype == 'nonbsa' ? '0' : '1';

            $key = $isbn . '-' . $bsa . '-' . trim((string) $pernr);
            $breakdown = $breakdownGrouped->has($key) ? $breakdownGrouped[$key] : collect();

            $breakdownList = $breakdown->map(function ($b) {
                return $b['BRANCHWHOUSE'] . '<span class="text-warning fw-bold">(' . $b['TOTALPROJTN'] . ')</span>';
            })->implode(', ');

            $pernrnameDisplay = '<span title="' . $pernrname . '" class="line-clamp-1">' . $pernr . ' ' . $pernrname . '</span>';

            $response[] = [
                'num'         => $num,
                'isbn'        => $isbn,
                'description' => $descriptionDisplay,
                'type'        => $alloctype,
                'allocation'  => $allocation,
                'pernr'       => $pernr,
                'ordered'     => $orderedDisplay,
                'pernrname'   => $pernrnameDisplay,
                'projection'  => $projection,
                'breakdown'   => '*' . $breakdownList,
            ];
        }

        return $response;
    }

    /**
     * PERNRs whose DIVISION contains the given value.
     *
     * The old query ran UPPER(LTRIM(RTRIM(DIVISION))) LIKE '%X%', which cannot use
     * an index and forced a full scan. The user table is small, so it is read
     * once and filtered in PHP.
     *
     * @return array<int, string>
     */
    private function pernrsForDivision(string $division): array
    {
        $needle = strtoupper(trim($division));

        return OPTv2User::query()
            ->select('PERNR', 'DIVISION')
            ->get()
            ->filter(function ($user) use ($needle) {
                return str_contains(strtoupper(trim((string) $user->DIVISION)), $needle);
            })
            ->map(function ($user) {
                return $user->PERNR;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * ISBN => DESCRIPTION, newest PROJECTIOND row winning.
     *
     * The old per-row lookup had no ORDER BY, so it returned an arbitrary row.
     *
     * @param  array<int, string> $isbns
     * @return array<string, string|null>
     */
    private function descriptionMap(array $isbns): array
    {
        $map = [];

        foreach (array_chunk($isbns, self::LOOKUP_CHUNK) as $chunk) {
            $rows = OPTv2Projectiond::query()
                ->select('EAN11', 'DESCRIPTION')
                ->whereIn('EAN11', $chunk)
                ->orderByDesc('id')
                ->get();

            foreach ($rows as $row) {
                if (!array_key_exists($row->EAN11, $map)) {
                    $map[$row->EAN11] = $row->DESCRIPTION;
                }
            }
        }

        return $map;
    }

    /**
     * PERNR => PERNRNAME, newest PROJECTIONH row winning.
     *
     * @param  array<int, string> $pernrs
     * @return array<string, string|null>
     */
    private function nameMap(array $pernrs): array
    {
        $map = [];

        foreach (array_chunk($pernrs, self::LOOKUP_CHUNK) as $chunk) {
            $rows = OPTv2Projectionh::query()
                ->select('PERNR', 'PERNRNAME')
                ->whereIn('PERNR', $chunk)
                ->orderByDesc('id')
                ->get();

            foreach ($rows as $row) {
                if (!array_key_exists($row->PERNR, $map)) {
                    $map[$row->PERNR] = $row->PERNRNAME;
                }
            }
        }

        return $map;
    }
}
