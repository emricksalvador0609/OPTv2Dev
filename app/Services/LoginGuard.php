<?php

namespace App\Services;

use App\Models\OPTv2Logs;
use Illuminate\Support\Facades\Cache;

/**
 * Per-address, per-username sign-in lockout.
 *
 * State lives in OPTV2LOGS rows, not the cache, so a lock can be cleared
 * selectively (audit screen button or `php artisan login:unlock`) without
 * deleting history and without wiping a shared cache. No schema change.
 *
 * Key is (client address + username): a shared office address locks only the
 * username that failed, never every user behind that address.
 */
class LoginGuard
{
    public const MAX_FAILURES = 3;

    public const LOCK_MINUTES = 15;

    public function __construct(private SignInAudit $audit)
    {
    }

    public function isLocked(string $username, string $ip): bool
    {
        return $this->failureCount($username, $ip) >= self::MAX_FAILURES;
    }

    /**
     * Cheap per-address ceiling checked before any query. Protects the
     * database and the log from a flood; never blocks normal use.
     * Fails open: a cache outage must not stop people signing in.
     */
    public function allowAttempt(string $ip): bool
    {
        try {
            $key = 'login_requests:' . $ip;
            $max = max(1, (int) config('login_throttle.max_requests', 600));
            $decay = max(1, (int) config('login_throttle.decay_seconds', 60));

            if (Cache::add($key, 1, $decay)) {
                return true;
            }

            return (int) Cache::increment($key) <= $max;
        } catch (\Throwable $e) {
            return true;
        }
    }

    /**
     * Record one failure. Writes at most MAX_FAILURES rows per window: the
     * attempt that reaches the limit is the last one counted.
     */
    public function recordFailure(string $username, string $ip): void
    {
        $attempt = $this->failureCount($username, $ip) + 1;
        if ($attempt > self::MAX_FAILURES) {
            return;
        }

        $this->audit->failed($username, $ip, $attempt);

        if ($attempt === self::MAX_FAILURES) {
            $this->audit->locked($username, $ip, self::LOCK_MINUTES);
        }
    }

    /**
     * Failures for this pair inside the window, ignoring anything recorded
     * before the newest unlock marker for the pair.
     */
    public function failureCount(string $username, string $ip): int
    {
        $query = OPTv2Logs::query()
            ->where('LOGTYPE', SignInAudit::FAILED)
            ->where('REFERENCE', trim($username))
            ->where('REMARKS', 'like', '%ip=' . $ip . ';%')
            ->where('DOCDATE', '>=', now()->subMinutes(self::LOCK_MINUTES)->format('Y-m-d H:i:s'));

        $marker = $this->unlockMarkerId($username, $ip);
        if ($marker > 0) {
            $query->where('id', '>', $marker);
        }

        return $query->count();
    }

    /**
     * Newest unlock marker that applies to this pair (its own address, or an
     * "all addresses" marker for the account).
     */
    public function unlockMarkerId(string $username, string $ip): int
    {
        $marker = OPTv2Logs::query()
            ->where('LOGTYPE', SignInAudit::UNLOCKED)
            ->where('REFERENCE', trim($username))
            ->where(function ($query) use ($ip) {
                $query->where('REMARKS', 'like', '%ip=' . $ip . ';%')
                    ->orWhere('REMARKS', 'like', '%ip=all;%');
            })
            ->max('id');

        return (int) $marker;
    }
}
