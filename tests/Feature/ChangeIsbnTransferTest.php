<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\OPTv2Projectiond;

/**
 * Coverage for the "Transfer" (Change ISBN) tab on Update Push List.
 *
 * Backend already existed and is exercised by the UI tab:
 *   GET  /datatable_changeisbn_projection_list  -> Mkacontrol::datatable_changeisbn_projection_list
 *   POST /submit_change_isbn_projection         -> Mkacontrol::submit_change_isbn_projection
 *
 * The test seeds and removes its own row (no RefreshDatabase: that would wipe
 * the connected testing database).
 */
class ChangeIsbnTransferTest extends TestCase
{
    private const TEST_BASEDOCNUM = '999001';
    private const TEST_TEMPISBN   = '9700TEMP_TESTCHG';

    protected function tearDown(): void
    {
        OPTv2Projectiond::where('BASEDOCNUM', self::TEST_BASEDOCNUM)->delete();

        parent::tearDown();
    }

    public function test_changeisbn_projection_list_returns_seeded_temp_isbn_row()
    {
        OPTv2Projectiond::create([
            'DOCNUM'      => self::TEST_BASEDOCNUM,
            'EAN11'       => '9999999990011',
            'TEMPEAN11'   => self::TEST_TEMPISBN,
            'BASEDOCNUM'  => self::TEST_BASEDOCNUM,
            'DESCRIPTION' => 'TEST TITLE CHANGE ISBN',
            'QTY'         => '3',
            'APPROVED'    => '1',
        ]);

        $response = $this->getJson(
            '/datatable_changeisbn_projection_list?basedocnum=' . self::TEST_BASEDOCNUM
        );

        $response->assertStatus(200)
            ->assertJsonFragment(['tempisbn' => self::TEST_TEMPISBN])
            ->assertJsonFragment(['descriptionval' => 'TEST TITLE CHANGE ISBN']);
    }

    public function test_change_isbn_projection_rejects_unknown_new_isbn()
    {
        $response = $this->postJson('/submit_change_isbn_projection', [
            'basedocnum'           => self::TEST_BASEDOCNUM,
            'change_isbn_existing' => self::TEST_TEMPISBN,
            'change_isbn_newisbn'  => '9999999999999',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 403]);

        // rejected request must not write an audit log for the transfer
        $this->assertDatabaseMissing('OPTV2LOGS', [
            'REFERENCE' => self::TEST_TEMPISBN,
            'LOGTYPE'   => 'changeisbn',
        ]);
    }
}
