<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LoginLockoutTest extends TestCase
{
    private const USERNAME = 'REGULAR.USER';

    private const PASSWORD = 'Str0ng!Passw0rd';

    protected function setUp(): void
    {
        parent::setUp();

        // Own in-memory schema: never touch a shared OPT database.
        foreach (array_keys(config('database.connections')) as $name) {
            DB::purge($name);
            config(["database.connections.$name" => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        }
        config(['database.default' => 'mysql', 'session.driver' => 'array', 'cache.default' => 'array']);
        config(['password_policy.mode' => 'warn', 'password_policy.notice_until' => null]);

        Schema::connection('mysql')->create('OPTV2USER', function (Blueprint $table) {
            $table->increments('id');
            foreach (['USERNAME', 'PASSWORD', 'ACTIVE', 'PERNR', 'RSM', 'SSM', 'RANK', 'APLEVEL', 'DIVISION', 'FULLNAME', 'USERGROUP', 'EMAIL', 'EXPGROUP'] as $column) {
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
            'id' => 2,
            'USERNAME' => self::USERNAME,
            'PASSWORD' => self::PASSWORD,
            'ACTIVE' => '1',
            'PERNR' => '2000002',
            'FULLNAME' => 'Regular User',
            'RANK' => 'AE',
        ]);
    }

    private function signIn(string $ip, string $password, string $username = self::USERNAME)
    {
        return $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post('/authenticate/login', ['username' => $username, 'password' => $password]);
    }

    public function test_three_failed_attempts_lock_that_username_for_that_address()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password')->assertSessionHas('errormessage');
        }

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'loginlocked',
            'REFERENCE' => self::USERNAME,
        ]);

        // Fourth attempt is refused even though the password is correct.
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionMissing('staff');
    }

    public function test_lock_does_not_affect_the_same_account_from_another_address()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $this->signIn('10.0.0.6', self::PASSWORD)->assertSessionHas('staff', 2);
    }

    public function test_lock_expires_after_fifteen_minutes()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        \Carbon\Carbon::setTestNow(now()->addMinutes(\App\Services\LoginGuard::LOCK_MINUTES + 1));
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);
    }

    public function test_only_three_failures_are_recorded_per_window()
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $this->assertSame(3, DB::table('OPTV2LOGS')->where('LOGTYPE', 'loginfailed')->count());
    }

    public function test_successful_sign_in_is_audited()
    {
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'login',
            'REFERENCE' => self::USERNAME,
            'USERID' => '2000002',
        ]);
    }

    public function test_sign_out_is_audited()
    {
        $this->withSession([
            'staff' => 2, 'pernr' => '2000002', 'rank' => 'AE', 'user_staff' => self::USERNAME,
        ])->get('/logout');

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'logout',
            'REFERENCE' => self::USERNAME,
        ]);
    }

    public function test_unlock_command_clears_the_lock_for_one_address()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $this->artisan('login:unlock', ['username' => self::USERNAME, '--ip' => '10.0.0.5'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'loginunlock',
            'REFERENCE' => self::USERNAME,
        ]);

        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);
    }

    public function test_no_log_row_contains_the_submitted_password()
    {
        $secret = 'Sup3rSecret!';

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', $secret);
        }

        $remarks = DB::table('OPTV2LOGS')->pluck('REMARKS')->implode(' ');

        $this->assertNotEmpty($remarks);
        $this->assertStringNotContainsString($secret, $remarks);
    }

    private function admin()
    {
        return $this->withSession([
            'staff' => 2, 'rank' => 'ADMIN', 'pernr' => '2000002', 'user_staff' => self::USERNAME,
        ]);
    }

    public function test_audit_endpoint_lists_sign_in_events_with_their_type()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }
        $this->signIn('10.0.0.6', self::PASSWORD);

        $rows = $this->admin()->getJson('/datatable_audit_log')->assertOk()->json()['data'];

        $events = array_column($rows, 'event');
        $this->assertContains('Sign-in Failed', $events);
        $this->assertContains('Sign-in Locked', $events);
        $this->assertContains('Signed In', $events);
        $this->assertStringNotContainsString('wrong-password', json_encode($rows));
    }

    public function test_audit_rows_expose_the_address_and_unlockable_state()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $rows = $this->admin()->getJson('/datatable_audit_log')->assertOk()->json()['data'];
        $locked = collect($rows)->firstWhere('event', 'Sign-in Locked');

        $this->assertSame('10.0.0.5', $locked['ip']);
        $this->assertTrue($locked['unlock']);
    }

    public function test_unlock_endpoint_clears_the_lock_for_that_address()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $this->admin()->post('/audit-log/unlock', [
            'username' => self::USERNAME, 'ip' => '10.0.0.5',
        ])->assertRedirect();

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'loginunlock', 'REFERENCE' => self::USERNAME,
        ]);

        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);
    }

    public function test_unlock_endpoint_is_admin_only()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        DB::table('OPTV2USER')->insert(['id' => 3, 'USERNAME' => 'OTHER.USER', 'PASSWORD' => 'x', 'ACTIVE' => '1', 'RANK' => 'AE']);

        $this->withSession(['staff' => 3, 'rank' => 'AE'])->post('/audit-log/unlock', [
            'username' => self::USERNAME, 'ip' => '10.0.0.5',
        ])->assertForbidden();

        $this->assertDatabaseMissing('OPTV2LOGS', ['LOGTYPE' => 'loginunlock']);

        // Still locked: a correct password cannot replace the existing identity.
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 3);
    }

    public function test_failed_attempts_write_a_fail2ban_readable_line()
    {
        $dir = storage_path('logs');
        config(['login_throttle.log_dir' => $dir]);
        array_map('unlink', glob($dir . '/auth-*.log') ?: []);

        $this->signIn('10.0.0.5', 'wrong-password')->assertSessionHas('errormessage');

        $files = glob($dir . '/auth-*.log') ?: [];
        $this->assertNotEmpty($files);

        $contents = (string) file_get_contents($files[0]);
        $this->assertStringContainsString(
            'security.login.failed ip=10.0.0.5 username=' . self::USERNAME,
            $contents
        );
        $this->assertStringNotContainsString('wrong-password', $contents);

        array_map('unlink', $files);
    }

    public function test_token_sign_in_is_audited()
    {
        $token = secureTokenWithId('2', 8);

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.9'])->get('/403?tk=' . $token);

        $this->assertDatabaseHas('OPTV2LOGS', [
            'LOGTYPE' => 'login',
            'REFERENCE' => self::USERNAME,
            'USERID' => '2000002',
        ]);
    }

    public function test_unlock_endpoint_rejects_a_non_address_value()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $this->admin()->postJson('/audit-log/unlock', [
            'username' => self::USERNAME, 'ip' => 'all; ip=10.0.0.6',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('OPTV2LOGS', ['LOGTYPE' => 'loginunlock']);
    }

    public function test_unlock_command_rejects_a_non_address_value()
    {
        $this->artisan('login:unlock', ['username' => self::USERNAME, '--ip' => 'not-an-ip'])
            ->assertExitCode(1);

        $this->assertDatabaseMissing('OPTV2LOGS', ['LOGTYPE' => 'loginunlock']);
    }

    public function test_unlock_for_one_address_leaves_the_other_locked()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
            $this->signIn('10.0.0.6', 'wrong-password');
        }

        $this->artisan('login:unlock', ['username' => self::USERNAME, '--ip' => '10.0.0.5'])
            ->assertExitCode(0);

        $this->signIn('10.0.0.6', self::PASSWORD)->assertSessionMissing('staff');
    }

    public function test_an_address_is_cut_off_above_the_request_ceiling()
    {
        config(['login_throttle.max_requests' => 2, 'login_throttle.decay_seconds' => 60]);

        $this->signIn('10.0.0.5', 'wrong-password');
        $this->signIn('10.0.0.5', 'wrong-password');

        $this->signIn('10.0.0.5', 'wrong-password')->assertSessionHas('errormessage');
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionMissing('staff');

        $this->assertSame(2, DB::table('OPTV2LOGS')->where('LOGTYPE', 'loginfailed')->count());
    }

    public function test_another_address_is_not_affected_by_the_ceiling()
    {
        config(['login_throttle.max_requests' => 1, 'login_throttle.decay_seconds' => 60]);

        $this->signIn('10.0.0.5', 'wrong-password');
        $this->signIn('10.0.0.5', 'wrong-password')->assertSessionHas('errormessage');

        $this->signIn('10.0.0.7', self::PASSWORD)->assertSessionHas('staff', 2);
    }

    public function test_unlocked_rows_stop_offering_the_unlock_action()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
        }

        $before = collect($this->admin()->getJson('/datatable_audit_log')->json()['data'])
            ->firstWhere('event', 'Sign-in Locked');
        $this->assertTrue($before['unlock']);

        $this->admin()->post('/audit-log/unlock', [
            'username' => self::USERNAME, 'ip' => '10.0.0.5',
        ])->assertRedirect();

        $after = collect($this->admin()->getJson('/datatable_audit_log')->json()['data'])
            ->firstWhere('event', 'Sign-in Locked');
        $this->assertFalse($after['unlock']);
    }

    public function test_unlock_endpoint_clears_one_address_and_leaves_the_other_rows_actionable()
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->signIn('10.0.0.5', 'wrong-password');
            $this->signIn('10.0.0.6', 'wrong-password');
        }

        $this->admin()->post('/audit-log/unlock', [
            'username' => self::USERNAME, 'ip' => '10.0.0.5',
        ])->assertRedirect();

        // The cleared pair works; the other address is still locked.
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);
        $this->flushSession();
        $this->signIn('10.0.0.6', self::PASSWORD)->assertSessionMissing('staff');

        $rows = $this->admin()->getJson('/datatable_audit_log')->json()['data'];
        $stillLocked = collect($rows)->first(fn ($row) => $row['event'] === 'Sign-in Locked' && $row['ip'] === '10.0.0.6');
        $this->assertNotNull($stillLocked);
        $this->assertTrue($stillLocked['unlock']);
    }

    public function test_a_repeated_sign_in_within_five_minutes_is_not_logged_twice()
    {
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);
        $this->flushSession();
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);

        $this->assertSame(1, DB::table('OPTV2LOGS')->where('LOGTYPE', 'login')->count());

        \Carbon\Carbon::setTestNow(now()->addMinutes(6));
        $this->flushSession();
        $this->signIn('10.0.0.5', self::PASSWORD)->assertSessionHas('staff', 2);

        $this->assertSame(2, DB::table('OPTV2LOGS')->where('LOGTYPE', 'login')->count());
    }

    public function test_a_repeated_sign_out_within_five_minutes_is_not_logged_twice()
    {
        $session = ['staff' => 2, 'pernr' => '2000002', 'rank' => 'AE', 'user_staff' => self::USERNAME];

        $this->withSession($session)->get('/logout');
        $this->withSession($session)->get('/logout');

        $this->assertSame(1, DB::table('OPTV2LOGS')->where('LOGTYPE', 'logout')->count());
    }

    public function test_the_security_log_timestamp_is_utc()
    {
        $dir = storage_path('logs');
        config(['login_throttle.log_dir' => $dir]);
        array_map('unlink', glob($dir . '/auth-*.log') ?: []);

        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2026, 11, 27, 8, 46, 18, 'UTC'));

        $this->signIn('10.0.0.5', 'wrong-password');

        $files = glob($dir . '/auth-*.log') ?: [];
        $this->assertNotEmpty($files);
        $this->assertStringStartsWith(
            '[2026-11-27 08:46:18] security.login.failed',
            (string) file_get_contents($files[0])
        );

        array_map('unlink', $files);
    }

    public function test_the_file_line_is_written_once_per_address_per_minute()
    {
        $dir = storage_path('logs');
        config(['login_throttle.log_dir' => $dir]);
        array_map('unlink', glob($dir . '/auth-*.log') ?: []);

        $this->signIn('10.0.0.5', 'wrong-password');
        $this->signIn('10.0.0.5', 'wrong-password');

        $files = glob($dir . '/auth-*.log') ?: [];
        $lines = substr_count((string) file_get_contents($files[0]), 'security.login.failed ip=10.0.0.5');
        $this->assertSame(1, $lines);

        array_map('unlink', $files);
    }
}
