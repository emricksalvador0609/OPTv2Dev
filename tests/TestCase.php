<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\DatabaseTargetGuard;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication {
        createApplication as private bootApplication;
    }

    /**
     * Boots the app, then refuses to continue when a SQL Server connection
     * targets the shared testing server. Runs before `setUpTraits()`, so
     * `RefreshDatabase` never gets the chance to drop REY tables.
     */
    public function createApplication()
    {
        $app = $this->bootApplication();

        DatabaseTargetGuard::assertSafe(config('database.connections'));

        return $app;
    }
}
