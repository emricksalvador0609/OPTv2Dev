<?php

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {
        return $this->guard($request) ?? view('audit_log');
    }

    public function data(Request $request, PasswordAudit $passwords, SignInAudit $signIns)
    {
        $blocked = $this->guard($request);
        if ($blocked) {
            return $blocked;
        }

        $entries = $passwords->passwordChanges()->concat($signIns->entries());
        $unlocked = $signIns->unlockIndex();

        $ordered = $entries->sort(function ($left, $right) {
            $byTime = strcmp((string) $right->DOCDATE, (string) $left->DOCDATE);

            return $byTime !== 0 ? $byTime : ((int) $right->id <=> (int) $left->id);
        })->values();

        $rows = [];
        $number = 0;
        foreach ($ordered as $entry) {
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

        return response()->json($rows);
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
