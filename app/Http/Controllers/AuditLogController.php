<?php

namespace App\Http\Controllers;

use App\Models\OPTv2Logs;
use App\Services\PasswordAudit;
use App\Services\SignInAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogController extends Controller
{
    /**
     * LOGTYPE => text shown in the Event column.
     */
    private const EVENTS = [
        PasswordAudit::LOG_TYPE => 'Password Changed',
        SignInAudit::FAILED => 'Sign-in Failed',
        SignInAudit::LOCKED => 'Sign-in Locked',
        SignInAudit::SUCCESS => 'Signed In',
        SignInAudit::LOGOUT => 'Signed Out',
        SignInAudit::UNLOCKED => 'Lockout Cleared',
    ];

    /**
     * DataTables column index => OPTV2LOGS column. Integer keys are the
     * indexes sent by the browser; only these may reach the ORDER BY.
     */
    private const ORDER_COLUMNS = [
        1 => 'REFERENCE',
        2 => 'USERID',
        3 => 'LOGTYPE',
        4 => 'REMARKS',
        5 => 'DOCDATE',
    ];

    private const DEFAULT_WINDOW_DAYS = 90;

    private const MAX_PAGE = 1000;

    public function index(Request $request)
    {
        return $this->guard($request) ?? view('audit_log');
    }

    public function data(Request $request, SignInAudit $signIns)
    {
        $blocked = $this->guard($request);
        if ($blocked) {
            return $blocked;
        }

        $draw = (int) $request->input('draw', 0);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = $length < 1 ? self::MAX_PAGE : min($length, self::MAX_PAGE);
        $search = trim((string) $request->input('search.value', ''));

        $query = OPTv2Logs::query()->whereIn('LOGTYPE', array_keys(self::EVENTS));
        $this->applyWindow($query, $request);

        $total = (clone $query)->count();

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('REFERENCE', 'like', $like)
                    ->orWhere('USERID', 'like', $like)
                    ->orWhere('REMARKS', 'like', $like)
                    ->orWhere('LOGTYPE', 'like', $like);
            });
        }

        $filtered = (clone $query)->count();

        $column = self::ORDER_COLUMNS[(int) $request->input('order.0.column', 5)] ?? 'DOCDATE';
        $direction = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $entries = $query->orderBy($column, $direction)
            ->orderBy('id', $direction)
            ->skip($start)
            ->take($length)
            ->get();

        $unlocked = $signIns->unlockIndex();

        $rows = [];
        $number = $start;
        foreach ($entries as $entry) {
            $type = (string) $entry->LOGTYPE;
            $username = trim((string) $entry->REFERENCE);
            $ip = $signIns->ipFromRemarks($entry->REMARKS);
            $isLockEvent = in_array($type, [SignInAudit::FAILED, SignInAudit::LOCKED], true);

            $rows[] = [
                'num' => ++$number,
                'reference' => $entry->REFERENCE,
                'userid' => $entry->USERID,
                'event' => self::EVENTS[$type] ?? Str::headline($type),
                'remarks' => Str::headline((string) $entry->REMARKS),
                'ip' => $ip,
                'unlock' => $isLockEvent && $ip !== null
                    && ($unlocked[$username]['all'] ?? 0) < (int) $entry->id
                    && ($unlocked[$username][$ip] ?? 0) < (int) $entry->id,
                'docdate' => $entry->DOCDATE
                    ? Carbon::parse($entry->DOCDATE)->format('d M Y, g:i:s A')
                    : '',
                'timestamp' => $entry->DOCDATE
                    ? Carbon::parse($entry->DOCDATE)->format('Y-m-d H:i:s')
                    : '',
            ];
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows,
        ]);
    }

    /**
     * Clear a lockout by writing an unlock marker. History is never deleted.
     */
    public function unlock(Request $request, SignInAudit $audit)
    {
        $blocked = $this->guard($request);
        if ($blocked) {
            return $blocked;
        }

        $data = $request->validate([
            'username' => 'required|string|max:50',
            'ip' => 'nullable|ip',
        ]);

        $ip = trim((string) ($data['ip'] ?? ''));
        $audit->unlocked((string) $data['username'], $ip === '' ? null : $ip, 'audit screen');

        return back()->with('successmessage', 'Lockout cleared for ' . $data['username'] . '.');
    }

    /**
     * Default window keeps the query bounded. Both bounds are optional and
     * ignored when unreadable, so a bad query string cannot empty the screen.
     */
    private function applyWindow($query, Request $request): void
    {
        $from = $this->date($request->input('from'));
        $to = $this->date($request->input('to'));

        $query->where(
            'DOCDATE',
            '>=',
            ($from ?? now()->subDays(self::DEFAULT_WINDOW_DAYS))->startOfDay()->format('Y-m-d H:i:s')
        );

        if ($to) {
            $query->where('DOCDATE', '<=', $to->endOfDay()->format('Y-m-d H:i:s'));
        }
    }

    private function date($value): ?Carbon
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Admins only. Returns a response to send back, or null when the request may continue.
     */
    private function guard(Request $request)
    {
        if (!$request->session()->has('staff')) {
            return redirect()->route('login_page');
        }

        if (trim((string) $request->session()->get('rank')) !== 'ADMIN') {
            abort(403);
        }

        return null;
    }
}
