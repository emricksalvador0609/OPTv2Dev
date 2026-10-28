<?php

namespace App\Http\Controllers;

use App\Services\PasswordAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        return $this->guard($request) ?? view('audit_log');
    }

    public function data(Request $request, PasswordAudit $audit)
    {
        $blocked = $this->guard($request);
        if ($blocked) {
            return $blocked;
        }

        $rows = [];
        $number = 0;
        foreach ($audit->passwordChanges() as $entry) {
            $rows[] = [
                'num' => ++$number,
                'reference' => $entry->REFERENCE,
                'userid' => $entry->USERID,
                'remarks' => \Illuminate\Support\Str::headline((string) $entry->REMARKS),
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
