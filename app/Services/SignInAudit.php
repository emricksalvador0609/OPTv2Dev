<?php

namespace App\Services;

use App\Models\OPTv2Logs;
use Illuminate\Support\Facades\Log;

/**
 * Records sign-in activity in OPTV2LOGS.
 *
 * Failures are the same rows the lockout counter reads, so this service is the
 * single source of truth for both the audit screen and App\Services\LoginGuard.
 * Never store the submitted password or anything derived from it.
 */
class SignInAudit
{
    public const FAILED = 'loginfailed';

    public const LOCKED = 'loginlocked';

    public const SUCCESS = 'login';

    public const LOGOUT = 'logout';

    public const UNLOCKED = 'loginunlock';

    /**
     * One row per failed attempt inside an unlocked window. Returns the row id.
     */
    public function failed(string $username, string $ip, int $attempt): int
    {
        $this->file('security.login.failed', $username, $ip);

        return $this->write($username, 'failed sign-in #' . $attempt . '; ip=' . $ip . ';', null, self::FAILED);
    }

    /**
     * Attempt refused while the pair is already locked. File log only: an
     * attacker must not be able to fill the audit table.
     */
    public function refusedWhileLocked(string $username, string $ip): void
    {
        $this->file('security.login.refused', $username, $ip);
    }

    public function locked(string $username, string $ip, int $minutes): void
    {
        $this->file('security.login.locked', $username, $ip);

        $this->write(
            $username,
            'locked after ' . LoginGuard::MAX_FAILURES . ' failed sign-ins for ' . $minutes . ' minutes; ip=' . $ip . ';',
            null,
            self::LOCKED
        );
    }

    /**
     * One line per event for the server-side blocker (fail2ban filter reads it).
     * Format is fixed on purpose: `security.login.<event> ip=<address> username=<name>`.
     */
    private function file(string $event, string $username, string $ip): void
    {
        try {
            Log::channel('auth')->info($event . ' ip=' . $ip . ' username=' . trim($username));
        } catch (\Throwable $e) {
            // A missing channel must never block sign-in.
        }
    }

    public function succeeded(\App\Models\OPTv2User $user, string $ip): void
    {
        $this->write(
            trim((string) $user->USERNAME),
            'signed in; ip=' . $ip . ';',
            (string) $user->PERNR,
            self::SUCCESS
        );
    }

    public function loggedOut(?string $username, ?string $pernr): void
    {
        $username = trim((string) $username);
        if ($username === '') {
            return;
        }

        $this->write($username, 'signed out;', $pernr, self::LOGOUT);
    }

    /**
     * Unlock marker. Applies to one address, or to every address for the
     * account when $ip is null. The guard ignores failures recorded before it.
     */
    public function unlocked(string $username, ?string $ip, string $by): void
    {
        $this->write(
            $username,
            'unlocked by ' . $by . '; ip=' . ($ip === null || $ip === '' ? 'all' : $ip) . ';',
            null,
            self::UNLOCKED
        );
    }

    /**
     * All sign-in entries, newest first.
     */
    public function entries()
    {
        return OPTv2Logs::query()
            ->whereIn('LOGTYPE', [self::FAILED, self::LOCKED, self::SUCCESS, self::LOGOUT, self::UNLOCKED])
            ->orderByDesc('DOCDATE')
            ->orderByDesc('id')
            ->get();
    }

    public function ipFromRemarks(?string $remarks): ?string
    {
        if (preg_match('/ip=([^;]+);/', (string) $remarks, $match)) {
            return $match[1] === 'all' ? null : $match[1];
        }

        return null;
    }

    /**
     * Newest unlock marker id per account and address.
     *
     * @return array<string, array<string, int>>  username => ['all' => id, '10.0.0.5' => id]
     */
    public function unlockIndex(): array
    {
        $index = [];

        foreach (OPTV2Logs::query()->where('LOGTYPE', self::UNLOCKED)->orderBy('id')->get() as $row) {
            $username = trim((string) $row->REFERENCE);
            if (!preg_match('/ip=([^;]+);/', (string) $row->REMARKS, $match)) {
                continue;
            }

            $index[$username][$match[1]] = max($index[$username][$match[1]] ?? 0, (int) $row->id);
        }

        return $index;
    }

    private function write(string $username, string $remarks, ?string $pernr, string $type): int
    {
        try {
            $row = OPTv2Logs::create([
                'REFERENCE' => trim($username),
                'REMARKS' => $remarks,
                'USERID' => $pernr,
                // now() follows Carbon::setTestNow and the Manila default set by Helper.php.
                'DOCDATE' => now()->format('Y-m-d H:i:s'),
                'LOGTYPE' => $type,
            ]);

            return (int) $row->id;
        } catch (\Throwable $e) {
            // Auditing must never block a sign-in attempt. Fail open on the counter.
            Log::warning('Sign-in audit log failed: ' . $e->getMessage());

            return 0;
        }
    }
}
