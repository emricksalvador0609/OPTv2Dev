<?php

namespace Tests\Support;

use RuntimeException;

/**
 * Refuses to boot the test suite when a SQL Server connection points at the
 * shared testing server (172.16.0.199) or at the live mirror databases.
 *
 * REY on that server is the mirror of live and carries the shared test data:
 * a `RefreshDatabase` run would drop its tables. Additive DDL is allowed by
 * project rule, destructive DDL is not, and no test may reach it by accident.
 */
class DatabaseTargetGuard
{
    /** Connection hosts that are safe: tests then run on the developer machine. */
    private const LOOPBACK_HOSTS = ['127.0.0.1', 'localhost', '::1'];

    /** Database names owned by the testing/live server, never a test target. */
    private const PROTECTED_DATABASES = ['REY', 'PRD'];

    /**
     * @param  array<string, array<string, mixed>>  $connections  config('database.connections')
     * @throws RuntimeException when any connection targets a shared server
     */
    public static function assertSafe(array $connections): void
    {
        foreach ($connections as $name => $config) {
            if (($config['driver'] ?? null) !== 'sqlsrv') {
                continue;
            }

            $host = strtolower(trim((string) ($config['host'] ?? '')));
            $database = strtoupper(trim((string) ($config['database'] ?? '')));

            if (!in_array($host, self::LOOPBACK_HOSTS, true)) {
                throw new RuntimeException(sprintf(
                    'Refusing to run tests: connection [%s] targets remote host [%s]. '
                    . 'REY on 172.16.0.199 is the live mirror; tests must run on the LOCAL '
                    . 'block (127.0.0.1) so they can never drop its tables.',
                    $name,
                    $host
                ));
            }

            if ($database !== '' && in_array($database, self::PROTECTED_DATABASES, true)) {
                throw new RuntimeException(sprintf(
                    'Refusing to run tests: connection [%s] targets protected database [%s]. '
                    . 'REY/PRD hold the shared testing data. Point .env at the LOCAL block '
                    . '(127.0.0.1/optv2_test) before running the suite.',
                    $name,
                    $database
                ));
            }
        }
    }
}
