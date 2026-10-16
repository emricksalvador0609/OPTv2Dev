<?php

namespace Tests\Feature;

use App\Services\ProjectionOmsOrderedService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Coverage for ProjectionOmsOrderedService — the OMS "Ordered" quantity that a
 * projection has already deducted from allocation.
 *
 * The projection link is the OMS batch/period pair
 * (prd.ZSD_OMS_SSY.BATCHID = projection DOCNUM, SCHOOLOPENING = projection PERIOD),
 * not the OMS creation date (ERDAT) the previous helper used. When the exact
 * school opening matches no order, the batch as a whole is counted.
 *
 * Order types: only ZKB1 is excluded. Uploaded rows (UPLOADTAG = 'X') count —
 * they are real orders; only rejected rows (DISAPPROVE = '1') are skipped.
 *
 * The test runs against an in-memory SQLite database, so it writes nothing to the
 * shared testing server. The default connection is swapped for the duration of the
 * test, with a second in-memory database attached as schema `prd` so the production
 * query text (`prd.ZSD_OMSH`, ...) runs unmodified.
 */
class ProjectionOmsOrderedServiceTest extends TestCase
{
    private const DOCNUM = '999137';
    private const PERIOD = '2';
    private const AE     = '00002367';
    private const ISBN   = '9789719822820';

    /** @var array|null */
    private $originalConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalConnection = config('database.connections.mysql');

        config(['database.connections.mysql' => [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]]);
        DB::purge('mysql');

        DB::connection('mysql')->statement("ATTACH DATABASE ':memory:' AS prd");

        Schema::connection('mysql')->create('OPTV2PROJECTIONPERIOD', function (Blueprint $table) {
            $table->increments('id');
            $table->string('DOCNUM')->nullable();
            $table->string('PERIOD')->nullable();
            $table->string('YEAR')->nullable();
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

        Schema::connection('mysql')->create('prd.ZSD_OMS_SSY', function (Blueprint $table) {
            $table->string('MANDT')->nullable();
            $table->string('ORDNUM')->nullable();
            $table->string('BATCHID')->nullable();
            $table->string('SCHOOLOPENING')->nullable();
        });
    }

    protected function tearDown(): void
    {
        config(['database.connections.mysql' => $this->originalConnection]);
        DB::purge('mysql');

        parent::tearDown();
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function seedPeriod(array $overrides = []): void
    {
        DB::connection('mysql')->table('OPTV2PROJECTIONPERIOD')->insert(array_merge([
            'DOCNUM' => self::DOCNUM,
            'PERIOD' => self::PERIOD,
            'YEAR'   => '2026-2027',
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function seedOrder(string $ordnum, array $overrides = []): void
    {
        $header = array_merge([
            'PERNR'      => self::AE,
            'SOTYPE'     => 'ZOR',
            'UPLOADTAG'  => '',
            'DISAPPROVE' => ' ',
            'ERDAT'      => '20260115',
        ], $overrides);

        DB::connection('mysql')->table('prd.ZSD_OMSH')->insert([
            'MANDT'      => '888',
            'ORDNUM'     => $ordnum,
            'PERNR'      => $header['PERNR'],
            'SOTYPE'     => $header['SOTYPE'],
            'UPLOADTAG'  => $header['UPLOADTAG'],
            'DISAPPROVE' => $header['DISAPPROVE'],
            'ERDAT'      => $header['ERDAT'],
        ]);

        DB::connection('mysql')->table('prd.ZSD_OMSD')->insert([
            'MANDT'      => '888',
            'ORDNUM'     => $ordnum,
            'MATNR'      => $overrides['MATNR'] ?? 'M0001',
            'EAN11'      => $overrides['EAN11'] ?? self::ISBN,
            'QTYORDERED' => $overrides['QTYORDERED'] ?? 10,
        ]);

        DB::connection('mysql')->table('prd.ZSD_OMS_SSY')->insert([
            'MANDT'         => '888',
            'ORDNUM'        => $ordnum,
            'BATCHID'       => $overrides['BATCHID'] ?? self::DOCNUM,
            'SCHOOLOPENING' => $overrides['SCHOOLOPENING'] ?? self::PERIOD,
        ]);
    }

    private function service(): ProjectionOmsOrderedService
    {
        return new ProjectionOmsOrderedService('mysql');
    }

    public function test_it_sums_ordered_qty_for_the_projection_batch_and_period(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 12.5, 'MATNR' => 'M0001']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 2.5, 'MATNR' => 'M0001']);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(15.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_ignores_orders_belonging_to_another_batch(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 7, 'BATCHID' => '999001']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 3, 'BATCHID' => self::DOCNUM]);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(3.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_counts_orders_from_the_batch_regardless_of_creation_year(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 4, 'ERDAT' => '20201231']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 6, 'ERDAT' => '20260101']);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(10.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_ignores_orders_for_another_school_opening_of_the_same_batch(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 9, 'SCHOOLOPENING' => '4']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 2, 'SCHOOLOPENING' => self::PERIOD]);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(2.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_keeps_quantities_of_different_isbn_and_ae_apart(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 5, 'EAN11' => self::ISBN, 'PERNR' => self::AE]);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 8, 'EAN11' => '9789719823582', 'PERNR' => self::AE]);
        $this->seedOrder('TTOMS03', ['QTYORDERED' => 11, 'EAN11' => self::ISBN, 'PERNR' => '00001877']);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE, '00001877']);

        $this->assertSame(5.0, $map[self::ISBN][self::AE]);
        $this->assertSame(8.0, $map['9789719823582'][self::AE]);
        $this->assertSame(11.0, $map[self::ISBN]['00001877']);
    }

    public function test_it_skips_the_cancelled_order_type_and_counts_the_rest(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 6, 'SOTYPE' => 'ZKB1']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 2, 'SOTYPE' => 'ZKB']);
        $this->seedOrder('TTOMS03', ['QTYORDERED' => 1, 'SOTYPE' => 'ZOR']);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(3.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_counts_uploaded_orders_and_skips_disapproved_ones(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 6, 'UPLOADTAG' => 'X']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 5, 'DISAPPROVE' => '1']);
        $this->seedOrder('TTOMS03', ['QTYORDERED' => 2]);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(8.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_falls_back_to_the_whole_batch_when_the_period_has_no_orders(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 9, 'SCHOOLOPENING' => '4']);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 3, 'SCHOOLOPENING' => '5']);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(12.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_counts_an_order_once_when_its_batch_link_is_duplicated(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 25]);

        // The OMS tables can carry the same order against the same batch and
        // opening more than once. The order quantity is still deducted once.
        DB::connection('mysql')->table('prd.ZSD_OMS_SSY')->insert([
            'MANDT'         => '888',
            'ORDNUM'        => 'TTOMS01',
            'BATCHID'       => self::DOCNUM,
            'SCHOOLOPENING' => self::PERIOD,
        ]);

        $map = $this->service()->orderedMap(self::DOCNUM, [self::AE]);

        $this->assertSame(25.0, $map[self::ISBN][self::AE]);
    }

    public function test_it_returns_nothing_when_the_projection_is_unknown(): void
    {
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 6]);

        $this->assertSame([], $this->service()->orderedMap('999999', [self::AE]));
    }

    public function test_ordered_by_isbn_sums_the_quantity_of_every_ae(): void
    {
        $this->seedPeriod();
        $this->seedOrder('TTOMS01', ['QTYORDERED' => 5, 'EAN11' => self::ISBN, 'PERNR' => self::AE]);
        $this->seedOrder('TTOMS02', ['QTYORDERED' => 7, 'EAN11' => self::ISBN, 'PERNR' => '00001877']);
        $this->seedOrder('TTOMS03', ['QTYORDERED' => 9, 'EAN11' => '9789719823582', 'PERNR' => self::AE]);

        $map = $this->service()->orderedByIsbn(self::DOCNUM, [self::AE, '00001877']);

        $this->assertSame(12.0, $map[self::ISBN]);
        $this->assertSame(9.0, $map['9789719823582']);
    }
}
