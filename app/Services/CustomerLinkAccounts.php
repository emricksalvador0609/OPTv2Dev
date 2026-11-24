<?php

namespace App\Services;

use App\Models\CrmMotherLookup;
use App\Models\OPTv2CustomerLink;
use App\Models\OPTv2User;
use Illuminate\Support\Facades\Cache;

/**
 * Customer Link Accounts list.
 *
 * The customer query joins two SAP mirror tables and groups ~28,000 rows. On the
 * live SQL Server (2008 R2) it swings between 4 and 98 seconds, so it must never
 * run on a page request.
 *
 * The grouped rows are kept in the cache and rebuilt in the background. Requests
 * always read the cached copy, even when it is old; a stale copy schedules a
 * rebuild for after the response is sent.
 */
class CustomerLinkAccounts
{
    public const TTL = 300;

    private const CACHE_PREFIX = 'customerlink:snapshot:';

    private const LOCK_FILE = 'customerlink-snapshot.lock';

    /**
     * @return array{rows: array<int, array<string, string>>, stale: bool, built_at: int}
     */
    public static function rows(array $aes): array
    {
        $key = self::cacheKey($aes);
        $cached = Cache::get($key);

        if (!is_array($cached) || !isset($cached['rows'])) {
            // First ever request for this team: nothing to serve, so build now.
            return [
                'rows' => self::rebuild($aes),
                'stale' => false,
                'built_at' => time(),
            ];
        }

        return [
            'rows' => $cached['rows'],
            'stale' => (time() - (int) ($cached['built_at'] ?? 0)) >= self::TTL,
            'built_at' => (int) ($cached['built_at'] ?? 0),
        ];
    }

    /**
     * Rebuild one team's snapshot. Keeps the previous copy when anything fails.
     *
     * @return array<int, array<string, string>>
     */
    public static function rebuild(array $aes): array
    {
        $key = self::cacheKey($aes);
        $lock = self::lock();

        $existing = Cache::get($key);
        $previous = is_array($existing) && isset($existing['rows']) ? $existing['rows'] : [];

        if ($lock === null) {
            // Another rebuild is already running; serve what we have.
            return $previous;
        }

        try {
            $rows = self::build($aes);

            Cache::put($key, ['built_at' => time(), 'rows' => $rows], now()->addDay());

            return $rows;
        } catch (\Throwable $e) {
            report($e);

            return $previous;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * The expensive query. Read-only against the SAP mirror.
     *
     * @return array<int, array<string, string>>
     */
    public static function build(array $aes): array
    {
        $aes = array_values(array_filter(array_map(fn ($a) => trim((string) $a), $aes), fn ($a) => $a !== ''));

        if ($aes === []) {
            return [];
        }

        $rows = CrmMotherLookup::whereIn('AE', $aes)
            ->leftJoin('prd.CRMMOTHERACT as t2', 'prd.CRMMOTHERLOOKUP.ACCTNO', '=', 't2.MOTHERACCT')
            ->selectRaw('
                MAX(prd.CRMMOTHERLOOKUP.NAME) as CUSTNAME,
                MAX(RTRIM(t2.CUSTNO)) as CUSTNO,
                MAX(RTRIM(t2.MOTHERACCT)) as MOTHERACCT,
                MAX(t2.AE) as AE,
                MAX(RTRIM(t2.DEPARTMENT)) as DEPARTMENT
            ')
            ->groupBy('t2.CUSTNO')
            ->get();

        $out = [];

        foreach ($rows as $row) {
            $custno = trim((string) ($row->CUSTNO ?? ''));

            if ($custno === '') {
                continue;
            }

            $out[] = [
                'custno' => $custno,
                'custname' => trim((string) ($row->CUSTNAME ?? '')),
                'motheracct' => trim((string) ($row->MOTHERACCT ?? '')),
                'ae' => trim((string) ($row->AE ?? '')),
                'department' => trim((string) ($row->DEPARTMENT ?? '')),
            ];
        }

        return $out;
    }

    /**
     * DataTables server-side parameters, normalised.
     *
     * @return array{draw: int, start: int, length: int, search: string, order: string, dir: string}
     */
    public static function requestParams($request): array
    {
        $columns = [0 => 'custno', 1 => 'custname', 2 => 'ae', 3 => 'topernr', 4 => null];

        $length = (int) $request->input('length', 25);
        $start = max(0, (int) $request->input('start', 0));

        if ($length <= 0 || $length > 200) {
            $length = 25;
        }

        $columnIndex = (int) $request->input('order.0.column', 1);
        $order = $columns[$columnIndex] ?? 'custname';

        if ($order === null) {
            $order = 'custname';
        }

        return [
            'draw' => (int) $request->input('draw', 1),
            'start' => $start,
            'length' => $length,
            'search' => trim((string) $request->input('search.value', '')),
            'order' => $order,
            'dir' => strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc',
        ];
    }

    /**
     * Filter, sort and slice one page out of the snapshot.
     *
     * @return array{rows: array<int, array<string, string>>, total: int, filtered: int}
     */
    public static function page(array $rows, array $params): array
    {
        $total = count($rows);

        $search = mb_strtolower(trim((string) ($params['search'] ?? '')));

        if ($search !== '') {
            $rows = array_values(array_filter($rows, function ($row) use ($search) {
                $haystack = mb_strtolower(implode(' ', [
                    $row['custno'] ?? '',
                    $row['custname'] ?? '',
                    $row['ae'] ?? '',
                    $row['department'] ?? '',
                    $row['topernr'] ?? '',
                    $row['topernrname'] ?? '',
                ]));

                return str_contains($haystack, $search);
            }));
        }

        $filtered = count($rows);
        $order = (string) ($params['order'] ?? 'custname');
        $dir = ($params['dir'] ?? 'asc') === 'desc' ? -1 : 1;

        usort($rows, function ($a, $b) use ($order, $dir) {
            $left = (string) ($a[$order] ?? '');
            $right = (string) ($b[$order] ?? '');

            return strnatcasecmp($left, $right) * $dir;
        });

        $start = max(0, (int) ($params['start'] ?? 0));
        $length = max(1, (int) ($params['length'] ?? 25));

        return [
            'rows' => array_slice($rows, $start, $length),
            'total' => $total,
            'filtered' => $filtered,
        ];
    }

    /**
     * Link targets for the rows on screen only.
     *
     * @return array<string, array{TOPERNR: string, TOPERNRNAME: string}>
     */
    public static function linkMap(array $custnos): array
    {
        $custnos = array_values(array_unique(array_filter($custnos, fn ($c) => trim((string) $c) !== '')));

        if ($custnos === []) {
            return [];
        }

        $map = [];

        $links = OPTv2CustomerLink::whereIn('CUSTOMER', $custnos)
            ->where('STATUS', '1')
            ->get();

        foreach ($links as $link) {
            $map[trim((string) $link->CUSTOMER)] = [
                'TOPERNR' => trim((string) ($link->TOPERNR ?? '')),
                'TOPERNRNAME' => trim((string) ($link->TOPERNRNAME ?? '')),
            ];
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    public static function aeNames(array $pernrs): array
    {
        $pernrs = array_values(array_unique(array_filter($pernrs, fn ($p) => trim((string) $p) !== '')));

        if ($pernrs === []) {
            return [];
        }

        $names = [];

        foreach (OPTv2User::whereIn('PERNR', $pernrs)->get() as $user) {
            $names[trim((string) $user->PERNR)] = (string) $user->FULLNAME;
        }

        return $names;
    }

    public static function cacheKey(array $aes): string
    {
        $aes = array_map(fn ($a) => trim((string) $a), $aes);
        sort($aes);

        return self::CACHE_PREFIX . sha1(implode('|', $aes));
    }

    /**
     * @return resource|null
     */
    private static function lock()
    {
        $path = storage_path('framework/' . self::LOCK_FILE);
        $handle = @fopen($path, 'c');

        if ($handle === false) {
            return null;
        }

        if (!flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);

            return null;
        }

        return $handle;
    }
}
