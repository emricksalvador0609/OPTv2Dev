<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * OMS quantity already ordered against a projection — the amount deducted from
 * that projection's allocation.
 *
 * The projection link is the OMS batch/period pair:
 *   prd.ZSD_OMS_SSY.BATCHID       = the projection id the app calls BASEDOCNUM / PID
 *   prd.ZSD_OMS_SSY.SCHOOLOPENING = the projection's PERIOD
 *                                   (OPTV2PROJECTIONPERIOD.PERIOD for that DOCNUM)
 *
 * The OMS tables are maintained externally and are read-only from OPTv2.
 *
 * Why the filters look like this (measured on the OMS mirror, 2026-10-16):
 *  - 46,357 orders carry a batch id, but only 476 of them have an empty
 *    UPLOADTAG. The older lookups required UPLOADTAG = '', so they only ever
 *    saw the hand-keyed minority and the column collapsed to 0 once the result
 *    was bound to a batch. "X" marks rows already pushed through the OMS
 *    interface — those are real orders, so UPLOADTAG is not filtered.
 *   - ZKB is the dominant order type of batch-tagged orders (62%); the previous
 *     whereNotIn(['ZKE','ZKA','ZKB']) dropped all of them. The original
 *     allocation query excluded ZKB1 only, so that is the exclusion used here.
 *
 * Both points are pending confirmation from the OMS team (which order types and
 * which upload state count as "deducted from the allocation").
 */
final class ProjectionOmsOrderedService
{
    /**
     * Order types that are not a deduction from the allocation. The original
     * allocation query on this column excluded ZKB1 only.
     *
     * @var array<int, string>
     */
    private const EXCLUDED_SOTYPES = ['ZKB1'];

    public function __construct(private string $connection = 'OMSL')
    {
    }

    /**
     * Ordered quantity per ISBN and AE for one projection.
     *
     * @param  string        $basedocnum projection id (BASEDOCNUM / PID), e.g. "260"
     * @param  array<string> $pernrs     AE / PERNR list; empty = every AE
     * @return array<string, array<string, float>> [EAN11][PERNR] => quantity
     */
    public function orderedMap(string $basedocnum, array $pernrs = []): array
    {
        $projectionPeriod = projection_period_details($basedocnum);

        if (!$projectionPeriod) {
            return [];
        }

        $opening = trim((string) $projectionPeriod->PERIOD);
        $rows = $this->rows($basedocnum, $pernrs, $opening !== '' ? $opening : null);

        // Some OMS rows carry the batch with a school opening other than the
        // projection's own period (one batch can cover several openings). When
        // the exact opening matches nothing, fall back to the whole batch so the
        // column still shows what was deducted from the allocation. The fallback
        // only runs when the exact match is empty, so no order is ever counted
        // twice.
        if ($rows->isEmpty()) {
            $rows = $this->rows($basedocnum, $pernrs, null);
        }

        $map = [];

        foreach ($rows as $row) {
            $map[$row->EAN11][trim($row->PERNR)] = (float) $row->OMSQTY;
        }

        return $map;
    }

    /**
     * Ordered quantity per ISBN for one projection, every AE summed.
     *
     * @param  string        $basedocnum projection id (PID), e.g. "137"
     * @param  array<string> $pernrs     AE / PERNR list; empty = every AE
     * @return array<string, float> [EAN11] => quantity
     */
    public function orderedByIsbn(string $basedocnum, array $pernrs = []): array
    {
        $map = [];

        foreach ($this->orderedMap($basedocnum, $pernrs) as $isbn => $perAe) {
            $map[$isbn] = array_sum($perAe);
        }

        return $map;
    }

    /**
     * One pass over the OMS order tables.
     *
     * @param  array<string>              $pernrs        AE / PERNR list; empty = every AE
     * @param  string|null                $schoolOpening NULL = every opening of the batch
     * @return \Illuminate\Support\Collection
     */
    private function rows(string $basedocnum, array $pernrs, ?string $schoolOpening)
    {
        $connection = DB::connection($this->connection);

        // The same order can be tagged to the same batch and opening more than
        // once in ZSD_OMS_SSY (19 such links on the OMS mirror). Joining the raw
        // table multiplied the item quantities once per duplicate tag, so the
        // link is reduced to one row per order first. The batch and opening are
        // filtered inside the subquery, so only the rows of the requested batch
        // are de-duplicated.
        $link = $connection->table('prd.ZSD_OMS_SSY')
            ->select('ORDNUM', 'MANDT')
            ->where('BATCHID', $basedocnum)
            ->when($schoolOpening !== null, function ($q) use ($schoolOpening) {
                $q->where('SCHOOLOPENING', $schoolOpening);
            })
            ->distinct();

        $query = $connection
            ->table('prd.ZSD_OMSH as t1')
            ->joinSub($link, 't3', function ($join) {
                $join->on('t3.ORDNUM', '=', 't1.ORDNUM')
                    ->on('t3.MANDT', '=', 't1.MANDT');
            })
            ->join('prd.ZSD_OMSD as t2', function ($join) {
                $join->on('t2.ORDNUM', '=', 't1.ORDNUM')
                    ->on('t2.MANDT', '=', 't1.MANDT');
            })
            ->where('t1.MANDT', '888')
            ->whereNotIn('t1.SOTYPE', self::EXCLUDED_SOTYPES)
            ->where('t1.DISAPPROVE', '<>', '1')
            ->whereNotNull('t2.EAN11')
            ->groupBy('t2.EAN11', 't1.PERNR')
            ->selectRaw('t2.EAN11 as EAN11, t1.PERNR as PERNR, SUM(t2.QTYORDERED) as OMSQTY');

        if (!empty($pernrs)) {
            $query->whereIn('t1.PERNR', $pernrs);
        }

        return $query->get();
    }
}
