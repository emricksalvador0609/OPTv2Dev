<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Support\DatabaseTargetGuard;

/**
 * The guard exists because REY on 172.16.0.199 is the mirror of live and holds
 * the shared test data. A `RefreshDatabase` run against it drops its tables.
 */
class DatabaseTargetGuardTest extends TestCase
{
    private function sqlsrvConnection(string $host, string $database = 'optv2_db'): array
    {
        return [
            'driver' => 'sqlsrv',
            'host' => $host,
            'port' => '1433',
            'database' => $database,
        ];
    }

    public function test_loopback_host_is_allowed()
    {
        DatabaseTargetGuard::assertSafe([
            'mysql' => $this->sqlsrvConnection('127.0.0.1'),
            'OMSL' => $this->sqlsrvConnection('localhost'),
        ]);

        $this->assertTrue(true);
    }

    public function test_sqlite_swap_is_allowed()
    {
        DatabaseTargetGuard::assertSafe([
            'mysql' => ['driver' => 'sqlite', 'database' => ':memory:', 'host' => null],
        ]);

        $this->assertTrue(true);
    }

    public function test_allowlisted_test_database_is_allowed()
    {
        DatabaseTargetGuard::assertSafe([
            'mysql' => $this->sqlsrvConnection('127.0.0.1', 'optv2_test'),
        ]);

        $this->assertTrue(true);
    }

    public function test_unknown_loopback_database_is_refused()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('optv2_live');

        DatabaseTargetGuard::assertSafe([
            'mysql' => $this->sqlsrvConnection('127.0.0.1', 'optv2_live'),
        ]);
    }

    public function test_testing_server_host_is_refused()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('172.16.0.199');

        DatabaseTargetGuard::assertSafe([
            'mysql' => $this->sqlsrvConnection('172.16.0.199', 'REY'),
        ]);
    }

    public function test_mirror_database_name_is_refused_even_on_loopback()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('REY');

        DatabaseTargetGuard::assertSafe([
            'mysql' => $this->sqlsrvConnection('127.0.0.1', 'REY'),
        ]);
    }
}
