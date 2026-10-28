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
        $this->withSession(['staff' => 1, 'rank' => 'AE'])->get('/audit-log')->assertForbidden();
    }

    public function test_only_password_change_entries_are_listed_newest_first()
    {
        $this->log('MARIA.S', '1500123', '2026-10-28 09:12:00');
        $this->log('JOSE.P', '1500456', '2026-10-28 15:40:00');
        $this->log('MARIA.S', '1500123', '2026-10-28 08:00:00', 'changeisbn', '9781234567890');

        $this->admin()->get('/audit-log')->assertOk()
            ->assertSeeInOrder(['JOSE.P', 'MARIA.S', 'password changed'])
            ->assertDontSee('9781234567890')
            ->assertSee('Password changes');
    }

    public function test_search_matches_username_or_employee_number()
    {
        $this->log('MARIA.S', '1500123', '2026-10-28 09:12:00');
        $this->log('JOSE.P', '1500456', '2026-10-28 15:40:00');

        $this->admin()->get('/audit-log?q=MARIA')->assertOk()
            ->assertSee('MARIA.S')->assertDontSee('JOSE.P');
        $this->admin()->get('/audit-log?q=1500456')->assertOk()
            ->assertSee('JOSE.P')->assertDontSee('MARIA.S');
    }

    public function test_empty_state_and_pagination()
    {
        $this->admin()->get('/audit-log')->assertOk()->assertSee('No password changes recorded yet.');

        foreach (range(1, 30) as $i) {
            $this->log('USER' . $i, '1500000', sprintf('2026-10-%02d 08:00:00', $i % 28 + 1));
        }

        $this->admin()->get('/audit-log')->assertOk()
            ->assertSee('of 30')->assertSee('Showing 1–25');
        $this->admin()->get('/audit-log?page=2')->assertOk()
            ->assertSee('Showing 26–30');
    }
}
