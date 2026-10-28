<?php

namespace App\Http\Controllers;

use App\Services\PasswordAudit;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request, PasswordAudit $audit)
    {
        if (!$request->session()->has('staff')) {
            return redirect()->route('login_page');
        }

        if (trim((string) $request->session()->get('rank')) !== 'ADMIN') {
            abort(403);
        }

        $search = trim((string) $request->query('q', ''));

        return view('audit_log', [
            'entries' => $audit->passwordChanges($search),
            'search' => $search,
        ]);
    }
}
