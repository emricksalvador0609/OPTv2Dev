<?php

namespace Tests\Support;

use RuntimeException;

/**
 * Fails closed: the test suite may only touch a local database whose name is
 * explicitly allowlisted. Anything else aborts before `setUpTraits()` runs,
 * so `RefreshDatabase` can never drop tables on the testing server
 * (172.16.0.199 / REY), on the live mirror, or on an unknown local database.
 */
class DatabaseTargetGuard
{
    /** Connection hosts that can only be this machine. */
    private const LOOPBACK_HOSTS = ['127.0.0.1', 'localhost', '::1'];

    /** Databases tests are allowed to create/drop rows and tables in. */
    private const TEST_DATABASES = ['optv2_test', 'optv2_db'];

    /**
     * @param  array<string, array<string, mixed>>  $connections  config('database.connections')
     * @throws RuntimeException when any connection targets anything not allowlisted
     */
    public static function assertSafe(array $connections): void
    {
        foreach ($connections as $name => $config) {
            if (($config['driver'] ?? null) !== 'sqlsrv') {
                continue;
            }

            $host = strtolower(trim((string) ($config['host'] ?? '')));
            $database = trim((string) ($config['database'] ?? ''));

            if (!in_array($host, self::LOOPBACK_HOSTS, true)) {
                throw new RuntimeException(sprintf(
                    'Refusing to run tests: connection [%s] targets remote host [%s]. '
                    . 'REY on 172.16.0.199 is the live mirror; tests must run on the LOCAL '
                    . 'block (127.0.0.1) so they can never drop its tables.',
                    $name,
                    $host
                ));
            }

            if (!in_array(strtolower($database), self::TEST_DATABASES, true)) {
                throw new RuntimeException(sprintf(
                    'Refusing to run tests: connection [%s] targets database [%s], which is not '
                    . 'an allowlisted test database (%s). REY/PRD and live databases are protected; '
                    . 'point .env at the LOCAL block (127.0.0.1/optv2_test) before running the suite.',
                    $name,
                    $database,
                    implode(', ', self::TEST_DATABASES)
                ));
            }
        }
    }
}
