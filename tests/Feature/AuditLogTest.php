<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Replace every configured connection before any query: never touch shared OPT data.
        foreach (array_keys(config('database.connections')) as $name) {
            DB::purge($name);
            config(["database.connections.$name" => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        }
        config(['database.default' => 'mysql', 'session.driver' => 'array', 'cache.default' => 'array']);
        config(['password_policy.mode' => 'enforce', 'password_policy.notice_until' => null]);

        Schema::connection('mysql')->create('OPTV2USER', function (Blueprint $table) {
            $table->increments('id');
            foreach (['USERNAME', 'PASSWORD', 'ACTIVE', 'PERNR', 'RSM', 'SSM', 'RANK', 'APLEVEL', 'DIVISION', 'FULLNAME', 'USERGROUP', 'EMAIL', 'EXPGROUP'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });
        Schema::connection('mysql')->create('SALESITUSERACCESS', function (Blueprint $table) {
            $table->increments('id');
            foreach (['USERID', 'MENU', 'PARENT', 'ACCESS'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });
        Schema::connection('mysql')->create('OPTV2LOGS', function (Blueprint $table) {
            $table->increments('id');
            foreach (['REFERENCE', 'REMARKS', 'USERID', 'DOCDATE', 'LOGTYPE', 'BASEDOCNUM'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });
        DB::table('OPTV2USER')->insert([
            'id' => 1, 'USERNAME' => 'ADMIN.USER', 'PASSWORD' => 'abcdefgh', 'ACTIVE' => '1',
            'PERNR' => '1000001', 'FULLNAME' => 'Admin User', 'USERGROUP' => 'admin', 'RANK' => 'ADMIN',
        ]);
        DB::table('SALESITUSERACCESS')->insert(['USERID' => 1, 'PARENT' => 'admin', 'MENU' => 'usersadmin', 'ACCESS' => 1]);
    }

    private function admin()
    {
        return $this->withSession([
            'staff' => 1, 'rank' => 'ADMIN', 'pernr' => '1000001', 'user_staff' => 'ADMIN.USER',
        ]);
    }

    private function log(string $reference, string $pernr, string $when, string $type = 'changepassword', string $remarks = 'password changed')
    {
        DB::table('OPTV2LOGS')->insert([
            'REFERENCE' => $reference, 'REMARKS' => $remarks, 'USERID' => $pernr,
            'DOCDATE' => $when, 'LOGTYPE' => $type,
        ]);
    }

    public function test_guests_are_redirected_and_non_admins_are_refused()
    {
        $this->get('/audit-log')->assertRedirect(route('login_page'));
        $this->get('/datatable_audit_log')->assertRedirect(route('login_page'));
        $this->withSession(['staff' => 1, 'rank' => 'AE'])->get('/audit-log')->assertForbidden();
        $this->withSession(['staff' => 1, 'rank' => 'AE'])->get('/datatable_audit_log')->assertForbidden();
    }

    public function test_page_renders_a_bootstrap_datatable()
    {
        $this->admin()->get('/audit-log')->assertOk()
            ->assertSee('id="audit-log-table"', false)
            ->assertSee('assets/js/features/audit_log.js', false)
            ->assertSee('assets/css/features/audit_log.css', false);
    }

    public function test_data_endpoint_returns_only_password_changes_newest_first()
    {
        $this->log('MARIA.S', '1500123', '2026-10-28 09:12:00');
        $this->log('JOSE.P', '1500456', '2026-10-28 15:40:00');
        $this->log('MARIA.S', '1500123', '2026-10-28 08:00:00', 'changeisbn', '9781234567890');

        $response = $this->admin()->getJson('/datatable_audit_log')->assertOk();
        $rows = $response->json();

        $this->assertCount(2, $rows);
        $this->assertSame(['JOSE.P', 'MARIA.S'], array_column($rows, 'reference'));
        $this->assertSame([1, 2], array_column($rows, 'num'));
        $this->assertSame('28 Oct 2026 15:40', $rows[0]['docdate']);
        $this->assertSame('password changed', $rows[0]['remarks']);
        $this->assertStringNotContainsString('9781234567890', $response->getContent());
    }

    public function test_data_endpoint_is_empty_when_nothing_is_logged()
    {
        $this->admin()->getJson('/datatable_audit_log')->assertOk()->assertExactJson([]);
    }
}
