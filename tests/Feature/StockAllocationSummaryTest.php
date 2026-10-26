<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Coverage for the Stock Allocation Summary datatable.
 *
 *   GET /datatable_reports_stockallocationsummary?basedocnum=..&pernr=..&division=..
 *
 * The endpoint used to run two correlated subqueries per returned row
 * (description from OPTV2PROJECTIOND, AE name from OPTV2PROJECTIONH) plus a
 * second OMS pass. On a 3,400-row projection that is ~6,800 index-less
 * subqueries and took 24 s on live. The service now fetches those lookups in
 * bulk, so the query count must not grow with the number of rows.
 *
 * The OMS "ordered" column stays keyed on the projection batch
 * (prd.ZSD_OMS_SSY.BATCHID = BASEDOCNUM) and its school opening
 * (SCHOOLOPENING = OPTV2PROJECTIONPERIOD.PERIOD), never on the OMS creation
 * year.
 *
 * The test runs entirely on SQLite (file-backed, shared by both connections),
 * so it writes nothing to the testing server.
 */
class StockAllocationSummaryTest extends TestCase
{
    private const PID    = '999137';
    private const PERIOD = '2';
    private const AE     = '00002367';
    private const ISBN   = '9789719822820';

    /** @var array|null */
    private $originalMysql;

    /** @var array|null */
    private $originalOmsl;

    private string $dbFile;

    private string $prdFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalMysql = config('database.connections.mysql');
        $this->originalOmsl = config('database.connections.OMSL');

        $this->dbFile = tempnam(sys_get_temp_dir(), 'optsas_');
        $this->prdFile = tempnam(sys_get_temp_dir(), 'optsasprd_');

        foreach (['mysql', 'OMSL'] as $connection) {
            config(["database.connections.{$connection}" => [
                'driver'   => 'sqlite',
                'database' => $this->dbFile,
                'prefix'   => '',
            ]]);
            DB::purge($connection);
            DB::connection($connection)->statement("ATTACH DATABASE '{$this->prdFile}' AS prd");
        }

        $this->createApplicationTables();
        $this->createOmsTables();
    }

    protected function tearDown(): void
    {
        config(['database.connections.mysql' => $this->originalMysql]);
        config(['database.connections.OMSL' => $this->originalOmsl]);
        DB::purge('mysql');
        DB::purge('OMSL');

        @unlink($this->dbFile);
        @unlink($this->prdFile);

        parent::tearDown();
    }

    private function createApplicationTables(): void
    {
        Schema::connection('mysql')->create('OPTV2ALLOCATED', function (Blueprint $table) {
            $table->increments('id');
            $table->string('MATNR')->nullable();
            $table->string('EAN11')->nullable();
            $table->string('BASEDOCNUM')->nullable();
            $table->string('ALLOCTYPE')->nullable();
            $table->string('QTY')->nullable();
            $table->string('PERNR')->nullable();
            $table->string('PROJECTION')->nullable();
        });

        Schema::connection('mysql')->create('OPTV2PROJECTIOND', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('DOCNUM')->nullable();
            $table->string('EAN11')->nullable();
            $table->string('BASEDOCNUM')->nullable();
            $table->string('DESCRIPTION')->nullable();
            $table->string('QTY')->nullable();
            $table->string('APPROVED')->nullable();
            $table->string('PERNR')->nullable();
            $table->string('BRANCHWHOUSE')->nullable();
        });

        Schema::connection('mysql')->create('OPTV2PROJECTIONH', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('DOCNUM')->nullable();
            $table->string('BASEDOCNUM')->nullable();
            $table->string('PERNR')->nullable();
            $table->string('PERNRNAME')->nullable();
            $table->string('BSA')->nullable();
        });

        Schema::connection('mysql')->create('OPTV2PROJECTIONPERIOD', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('DOCNUM')->nullable();
            $table->string('PERIOD')->nullable();
            $table->string('YEAR')->nullable();
        });

        Schema::connection('mysql')->create('OPTV2USER', function (Blueprint $table) {
            $table->increments('id');
            $table->string('PERNR')->nullable();
            $table->string('DIVISION')->nullable();
            $table->string('USERNAME')->nullable();
            $table->string('RANK')->nullable();
            $table->string('RSM')->nullable();
            $table->string('SSM')->nullable();
            $table->string('APLEVEL')->nullable();
        });
    }

    private function createOmsTables(): void
    {
        Schema::connection('mysql')->create('prd.ZSD_OMS_SSY', function (Blueprint $table) {
            $table->string('MANDT')->nullable();
            $table->string('ORDNUM')->nullable();
            $table->string('BATCHID')->nullable();
            $table->string('SCHOOLOPENING')->nullable();
        });

        Schema::connection('mysql')->create('prd.ZSD_OMSH', function (Blueprint $table) {
            $table->string('MANDT')->nullable();
            $table->string('ORDNUM')->nullable();
            $table->string('PERNR')->nullable();
            $table->string('SOTYPE')->nullable();
            $table->string('UPLOADTAG')->nullable();
            $table->string('DISAPPROVE')->nullable();
            $table->string('ERDAT')->nullable();
        });

        Schema::connection('mysql')->create('prd.ZSD_OMSD', function (Blueprint $table) {
            $table->string('MANDT')->nullable();
            $table->string('ORDNUM')->nullable();
            $table->string('MATNR')->nullable();
            $table->string('EAN11')->nullable();
            $table->decimal('QTYORDERED', 18, 2)->nullable();
        });
    }

    private function seedPeriod(string $period = self::PERIOD): void
    {
        DB::connection('mysql')->table('OPTV2PROJECTIONPERIOD')->insert([
            'DOCNUM' => (int) self::PID,
            'PERIOD' => $period,
            'YEAR'   => '2026-2027',
        ]);
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function seedAllocation(string $ean11, array $overrides = []): void
    {
        DB::connection('mysql')->table('OPTV2ALLOCATED')->insert(array_merge([
            'MATNR'      => 'M0001',
            'EAN11'      => $ean11,
            'BASEDOCNUM' => self::PID,
            'ALLOCTYPE'  => 'bsa',
            'QTY'        => '10',
            'PERNR'      => self::AE,
            'PROJECTION' => '5',
        ], $overrides));
    }

    /**
     * Description is taken from the newest PROJECTIOND row for that ISBN.
     */
    private function seedDescription(string $ean11, string $description, string $docnum = 'D1'): void
    {
        DB::connection('mysql')->table('OPTV2PROJECTIOND')->insert([
            'DOCNUM'       => $docnum,
            'EAN11'        => $ean11,
            'BASEDOCNUM'   => self::PID,
            'DESCRIPTION'  => $description,
            'QTY'          => '5',
            'APPROVED'     => '1',
            'PERNR'        => self::AE,
            'BRANCHWHOUSE' => 'BR1',
        ]);
    }

    private function seedHeader(string $pernr, string $pernrname, string $bsa = '1', string $docnum = 'D1'): void
    {
        DB::connection('mysql')->table('OPTV2PROJECTIONH')->insert([
            'DOCNUM'     => $docnum,
            'BASEDOCNUM' => self::PID,
            'PERNR'      => $pernr,
            'PERNRNAME'  => $pernrname,
            'BSA'        => $bsa,
        ]);
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function seedOrder(string $ordnum, array $overrides = []): void
    {
        DB::connection('mysql')->table('prd.ZSD_OMSH')->insert([
            'MANDT'      => '888',
            'ORDNUM'     => $ordnum,
            'PERNR'      => $overrides['PERNR'] ?? self::AE,
            'SOTYPE'     => $overrides['SOTYPE'] ?? 'ZOR',
            'UPLOADTAG'  => '',
            'DISAPPROVE' => $overrides['DISAPPROVE'] ?? ' ',
            'ERDAT'      => $overrides['ERDAT'] ?? '20260115',
        ]);

        DB::connection('mysql')->table('prd.ZSD_OMSD')->insert([
            'MANDT'      => '888',
            'ORDNUM'     => $ordnum,
            'MATNR'      => 'M0001',
            'EAN11'      => $overrides['EAN11'] ?? self::ISBN,
            'QTYORDERED' => $overrides['QTYORDERED'] ?? 10,
        ]);

        DB::connection('mysql')->table('prd.ZSD_OMS_SSY')->insert([
            'MANDT'         => '888',
            'ORDNUM'        => $ordnum,
            'BATCHID'       => $overrides['BATCHID'] ?? self::PID,
            'SCHOOLOPENING' => $overrides['SCHOOLOPENING'] ?? self::PERIOD,
        ]);
    }

    private function endpoint(array $params = [])
    {
        $query = http_build_query(array_merge([
            'basedocnum' => self::PID,
            'pernr'      => '1',
            'division'   => '1',
        ], $params));

        return $this->getJson('/datatable_reports_stockallocationsummary?' . $query);
    }

    public function test_it_returns_an_empty_result_when_the_projection_has_no_allocation(): void
    {
        $this->seedPeriod();

        $this->endpoint()->assertStatus(200)->assertJson(['num' => 0]);
    }

    public function test_it_enriches_each_row_from_one_bulk_lookup_per_source(): void
    {
        $this->seedPeriod();

        $this->seedDescription(self::ISBN, 'CURRENT TITLE');
        $this->seedHeader(self::AE, 'CURRENT NAME');

        $this->seedAllocation(self::ISBN);
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 7]);

        $body = $this->endpoint()->assertStatus(200)->json();

        $this->assertCount(1, $body);
        $row = $body[0];

        $this->assertSame(self::ISBN, $row['isbn']);
        $this->assertSame('10', $row['allocation']);
        $this->assertSame(self::AE, $row['pernr']);
        $this->assertSame('7', $row['ordered']);
        $this->assertSame('5', $row['projection']);
        $this->assertSame('bsa', $row['type']);
        $this->assertStringContainsString('CURRENT TITLE', $row['description']);
        $this->assertStringContainsString('CURRENT NAME', $row['pernrname']);
        $this->assertStringContainsString('BR1', $row['breakdown']);
        $this->assertStringContainsString('(5)', $row['breakdown']);
    }

    public function test_the_newest_description_and_ae_name_win(): void
    {
        $this->seedPeriod();

        $this->seedDescription(self::ISBN, 'OLD TITLE', 'DX');
        $this->seedDescription(self::ISBN, 'NEW TITLE', 'DY');
        $this->seedHeader(self::AE, 'OLD NAME', '0', 'DX');
        $this->seedHeader(self::AE, 'NEW NAME', '0', 'DY');

        $this->seedAllocation(self::ISBN);

        $row = $this->endpoint()->assertStatus(200)->json()[0];

        $this->assertStringContainsString('NEW TITLE', $row['description']);
        $this->assertStringContainsString('NEW NAME', $row['pernrname']);
    }

    public function test_it_ignores_allocation_rows_with_a_zero_projection(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedAllocation(self::ISBN, ['PROJECTION' => '0']);

        $this->endpoint()->assertStatus(200)->assertJson(['num' => 0]);
    }

    public function test_ordered_quantity_falls_back_to_the_whole_batch_when_the_period_has_no_orders(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedAllocation(self::ISBN);

        $this->seedOrder('TTOMS01', ['QTYORDERED' => 9, 'SCHOOLOPENING' => '4']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 3, 'SCHOOLOPENING' => '5']);

        $body = $this->endpoint()->assertStatus(200)->json();

        $this->assertSame('12', $body[0]['ordered']);
    }

    public function test_ordered_quantity_uses_only_the_exact_period_when_orders_carry_it(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedAllocation(self::ISBN);

        $this->seedOrder('TTOMS01', ['QTYORDERED' => 9, 'SCHOOLOPENING' => '4']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 2, 'SCHOOLOPENING' => self::PERIOD]);

        $body = $this->endpoint()->assertStatus(200)->json();

        $this->assertSame('2', $body[0]['ordered']);
    }

    public function test_ordered_quantity_ignores_another_batch_and_stays_batch_keyed(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedAllocation(self::ISBN);

        // same creation year, different batch: must not be counted
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 99, 'BATCHID' => '999001', 'ERDAT' => '20260115']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 4, 'ERDAT' => '20260115']);

        $body = $this->endpoint()->assertStatus(200)->json();

        $this->assertSame('4', $body[0]['ordered']);
    }

    public function test_division_filter_narrows_the_rows_and_pernr_filter_keeps_one_ae(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedHeader('00001877', 'OTHER AE');

        $this->seedAllocation(self::ISBN);
        $this->seedAllocation('9789719823582', ['PERNR' => '00001877']);

        DB::connection('mysql')->table('OPTV2USER')->insert([
            ['PERNR' => self::AE, 'DIVISION' => 'CTD'],
            ['PERNR' => '00001877', 'DIVISION' => 'MKT'],
        ]);

        $all = $this->endpoint()->assertStatus(200)->json();
        $this->assertCount(2, $all);

        $ctd = $this->endpoint(['division' => 'CTD'])->assertStatus(200)->json();
        $this->assertCount(1, $ctd);
        $this->assertSame(self::AE, $ctd[0]['pernr']);

        $oneAe = $this->endpoint(['pernr' => '00001877'])->assertStatus(200)->json();
        $this->assertCount(1, $oneAe);
        $this->assertSame('00001877', $oneAe[0]['pernr']);
    }

    public function test_query_count_does_not_grow_with_the_number_of_rows(): void
    {
        $this->seedPeriod();
        $this->seedDescription(self::ISBN, 'TITLE');
        $this->seedHeader(self::AE, 'NAME');
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 7]);

        $count = 0;
        $listener = function () use (&$count) {
            $count++;
        };
        DB::connection('mysql')->listen($listener);
        DB::connection('OMSL')->listen($listener);

        $this->seedAllocation(self::ISBN);
        $count = 0;
        $this->endpoint()->assertStatus(200);
        $small = $count;

        for ($i = 0; $i < 200; $i++) {
            $this->seedAllocation(sprintf('978971982%04d', $i));
        }

        $count = 0;
        $body = $this->endpoint()->assertStatus(200)->json();
        $large = $count;

        $this->assertCount(201, $body);
        $this->assertSame($small, $large, 'query count must not scale with the row count');
    }
}
