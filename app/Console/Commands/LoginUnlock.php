<?php

namespace App\Console\Commands;

use App\Services\LoginGuard;
use App\Services\SignInAudit;
use Illuminate\Console\Command;

/**
 * Break-glass recovery for a locked sign-in.
 *
 * Writes a loginunlock marker instead of deleting history, so the audit trail
 * stays complete. Works without a session, which is what makes it usable when
 * the locked account is the administrator's own.
 */
class LoginUnlock extends Command
{
    protected $signature = 'login:unlock
                            {username : Account username to unlock}
                            {--ip= : Only this client address; omit to unlock every address}';

    protected $description = 'Clear the sign-in lockout for an account (one address or all)';

    public function handle(LoginGuard $guard, SignInAudit $audit): int
    {
        $username = trim((string) $this->argument('username'));
        if ($username === '') {
            $this->error('A username is required.');

            return 1;
        }

        $ip = trim((string) $this->option('ip'));
        $ip = $ip === '' ? null : $ip;

        if ($ip !== null && filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $this->error('--ip must be a valid IPv4 or IPv6 address.');

            return 1;
        }

        $pending = $ip === null
            ? 'all addresses'
            : 'address ' . $ip . ' (' . $guard->failureCount($username, $ip) . ' recorded failure(s))';

        $audit->unlocked($username, $ip, 'command');
        $this->info('Unlocked ' . $username . ' for ' . $pending . '.');
        $this->line('Failures recorded before this marker are ignored by the lockout counter.');

        return 0;
    }
}
