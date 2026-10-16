<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * OMS quantity already ordered against a projection — the amount deducted from
 * that projection's allocation.
 *
 * The projection link is the OMS batch/period pair:
 *   prd.ZSD_OMS_SSY.BATCHID       = projection DOCNUM  (the app's "PID")
 *   prd.ZSD_OMS_SSY.SCHOOLOPENING = projection PERIOD
 *
 * This replaces the older year-based lookup (OMS orders whose ERDAT year matched
 * the projection's school year for the same AE), which never bound the orders to
 * the allocation they were deducted from.
 *
 * The OMS tables are maintained externally and are read-only from OPTv2.
 */
final class ProjectionOmsOrderedService
{
    public function __construct(private string $connection = 'OMSL')
    {
    }

    /**
     * Ordered quantity per ISBN and AE for one projection.
     *
     * @param  string        $basedocnum projection DOCNUM (PID), e.g. "137"
     * @param  array<string> $pernrs     AE / PERNR list; empty = every AE
     * @return array<string, array<string, float>> [EAN11][PERNR] => quantity
     */
    public function orderedMap(string $basedocnum, array $pernrs = []): array
    {
        $projectionPeriod = projection_period_details($basedocnum);

        if (!$projectionPeriod) {
            return [];
        }

        $query = DB::connection($this->connection)
            ->table('prd.ZSD_OMS_SSY as t3')
            ->join('prd.ZSD_OMSH as t1', function ($join) {
                $join->on('t1.ORDNUM', '=', 't3.ORDNUM')
                    ->on('t1.MANDT', '=', 't3.MANDT');
            })
            ->join('prd.ZSD_OMSD as t2', function ($join) {
                $join->on('t2.ORDNUM', '=', 't1.ORDNUM')
                    ->on('t2.MANDT', '=', 't1.MANDT');
            })
            ->where('t3.BATCHID', $basedocnum)
            ->where('t3.SCHOOLOPENING', (string) $projectionPeriod->PERIOD)
            ->where('t1.MANDT', '888')
            ->whereNotIn('t1.SOTYPE', ['ZKE', 'ZKA', 'ZKB'])
            ->where('t1.UPLOADTAG', '')
            ->where('t1.DISAPPROVE', '<>', '1')
            ->whereNotNull('t2.EAN11')
            ->groupBy('t2.EAN11', 't1.PERNR')
            ->selectRaw('t2.EAN11 as EAN11, t1.PERNR as PERNR, SUM(t2.QTYORDERED) as OMSQTY');

        if (!empty($pernrs)) {
            $query->whereIn('t1.PERNR', $pernrs);
        }

        $map = [];

        foreach ($query->get() as $row) {
            $map[$row->EAN11][trim($row->PERNR)] = (float) $row->OMSQTY;
        }

        return $map;
    }

    /**
     * Ordered quantity per ISBN for one projection, every AE summed together.
     *
     * Used where the caller reports per title rather than per AE (dashboard).
     *
     * @param  array<string> $pernrs AE / PERNR list; empty = every AE
     * @return array<string, float> [EAN11] => quantity
     */
    public function orderedByIsbn(string $basedocnum, array $pernrs = []): array
    {
        $map = [];

        foreach ($this->orderedMap($basedocnum, $pernrs) as $isbn => $byAe) {
            $map[$isbn] = array_sum($byAe);
        }

        return $map;
    }
}
