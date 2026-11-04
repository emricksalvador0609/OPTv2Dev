<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Coverage for the school-year window in ProjectionPeriodList().
 *
 * Every projection-period dropdown (dashboard, create projection, allocation,
 * convert, stock allocation, reports) goes through this helper. It used to
 * filter on the current calendar year only:
 *
 *     where('YEAR', 'LIKE', '%'.now()->year.'-%')
 *
 * so a period opened for the next school year (for example 2027-2028 opened
 * in November 2026) never appeared anywhere until January 1. It also dropped
 * the current school year as soon as the calendar rolled over, because
 * "2026-2027" does not match "%2027-%".
 *
 * The window is now previous, current and next school year, so an opened
 * period is visible immediately and an ended period stays listed as history.
 *
 * The test runs entirely on SQLite, so it writes nothing to the testing
 * server.
 */
class ProjectionPeriodYearWindowTest extends TestCase
{
    /** @var array|null */
    private $originalMysql;

    private string $dbFile;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-11-04 09:00:00'));

        $this->originalMysql = config('database.connections.mysql');

        $this->dbFile = tempnam(sys_get_temp_dir(), 'optppw_');

        config(['database.connections.mysql' => [
            'driver'   => 'sqlite',
            'database' => $this->dbFile,
            'prefix'   => '',
        ]]);
        DB::purge('mysql');

        Schema::connection('mysql')->create('OPTV2PROJECTIONPERIOD', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('DOCNUM')->nullable();
            $table->string('YEAR')->nullable();
            $table->string('PERIOD')->nullable();
            $table->string('LEVEL')->nullable();
            $table->string('STATUS')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        config(['database.connections.mysql' => $this->originalMysql]);
        DB::purge('mysql');

        @unlink($this->dbFile);

        parent::tearDown();
    }

    public function test_it_lists_previous_current_and_next_school_year_periods(): void
    {
        $this->seedPeriod('140', '2025-2026');
        $this->seedPeriod('139', '2026-2027');
        $this->seedPeriod('141', '2027-2028');

        $docnums = ProjectionPeriodList('0')->pluck('DOCNUM')->map(fn ($docnum) => (string) $docnum)->all();

        $this->assertContains('141', $docnums, 'a period opened for the next school year must be listed');
        $this->assertContains('139', $docnums, 'the current school year must be listed');
        $this->assertContains('140', $docnums, 'last school year must stay listed as history');
    }

    public function test_it_hides_periods_outside_the_three_year_window(): void
    {
        $this->seedPeriod('128', '2024-2025');
        $this->seedPeriod('139', '2026-2027');
        $this->seedPeriod('150', '2028-2029');

        $docnums = ProjectionPeriodList('0')->pluck('DOCNUM')->map(fn ($docnum) => (string) $docnum)->all();

        $this->assertContains('139', $docnums);
        $this->assertNotContains('128', $docnums);
        $this->assertNotContains('150', $docnums);
    }

    private function seedPeriod(string $docnum, string $year): void
    {
        DB::connection('mysql')->table('OPTV2PROJECTIONPERIOD')->insert([
            'DOCNUM' => $docnum,
            'YEAR'   => $year,
            'PERIOD' => '1',
            'LEVEL'  => 'BED',
            'STATUS' => '1',
        ]);
    }
}
