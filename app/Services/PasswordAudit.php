<?php

namespace App\Services;

use App\Models\OPTv2Logs;
use App\Models\OPTv2User;
use Illuminate\Support\Facades\Log;

class PasswordAudit
{
    public const LOG_TYPE = 'changepassword';

    public const REMARK_CHANGED = 'password changed';

    /**
     * Record a completed password change. Never store the password itself.
     */
    public function changed(OPTv2User $user, ?string $pernr): void
    {
        try {
            OPTv2Logs::create([
                'REFERENCE' => trim((string) $user->USERNAME),
                'REMARKS' => self::REMARK_CHANGED,
                'USERID' => $pernr,
                'DOCDATE' => date_now(),
                'LOGTYPE' => self::LOG_TYPE,
            ]);
        } catch (\Throwable $e) {
            // Auditing must never block a password change that already succeeded.
            Log::warning('Password audit log failed: ' . $e->getMessage());
        }
    }
}
