<?php

namespace Tests\Feature;

use App\Support\PasswordPolicy;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    public function test_policy_cache_avoids_repeated_queries_and_rechecks_policy_changes()
    {
        \Illuminate\Support\Facades\Route::middleware('web')->get('/password-policy-probe', function () {
            return response('Allowed');
        });
        DB::table('OPTV2USER')->where('id', 1)->update(['PASSWORD' => 'abcdefghijkl']);
        DB::connection('mysql')->enableQueryLog();
        DB::connection('mysql')->flushQueryLog();
        $this->withSession(['staff' => 1])->get('/password-policy-probe')->assertOk();
        $this->assertCount(1, DB::connection('mysql')->getQueryLog());
        for ($i = 0; $i < 10; $i++) {
            $this->get('/password-policy-probe')->assertOk();
        }
        $this->assertCount(1, DB::connection('mysql')->getQueryLog());
        config(['password_policy.minimum_length' => 14]);
        $this->get('/password-policy-probe')->assertRedirect(route('password.edit'));
        $this->assertCount(2, DB::connection('mysql')->getQueryLog());
        $this->getJson('/password-policy-probe')->assertStatus(403)
            ->assertJson(['code' => 'PASSWORD_CHANGE_REQUIRED']);
        $this->assertCount(2, DB::connection('mysql')->getQueryLog());
        $cached = session('password_policy_check');
        $cached['expires'] = 0;
        $this->withSession(['password_policy_check' => $cached])->get('/password-policy-probe')
            ->assertRedirect(route('password.edit'));
        $this->assertCount(3, DB::connection('mysql')->getQueryLog());
    }

    public function test_required_change_is_a_locked_dialog_and_success_clears_cached_decision()
    {
        $this->withSession(['staff' => 1])->get('/')->assertRedirect(route('password.edit'));
        $this->get('/change-password')->assertSee('id="required-password-dialog"', false)
            ->assertSee('id="password-back"', false)
            ->assertSee('assets/js/features/change_password.js', false)
            ->assertSee('assets/css/features/change_password.css', false)
            ->assertDontSee('<style>', false)
            ->assertDontSee('<script>', false);
        $this->assertStringContainsString('dialog.showModal()', file_get_contents(public_path('assets/js/features/change_password.js')));
        $this->post('/change-password', [
            'current_password' => 'old', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertSessionMissing('password_policy_check');
        $this->get('/change-password')->assertDontSee('<dialog', false);
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Replace every configured connection before any query: never touch shared OPT data.
        foreach (array_keys(config('database.connections')) as $name) {
            DB::purge($name);
            config(["database.connections.$name" => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        }
        config(['database.default' => 'mysql', 'session.driver' => 'array', 'cache.default' => 'array']);
        // Existing tests describe hard enforcement; warn-mode tests override this per test.
        config(['password_policy.mode' => 'enforce', 'password_policy.notice_until' => null]);
        Schema::connection('mysql')->create('OPTV2USER', function (Blueprint $table) {
            $table->increments('id');
            foreach (['USERNAME', 'PASSWORD', 'ACTIVE', 'PERNR', 'RSM', 'SSM', 'RANK', 'APLEVEL', 'DIVISION'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });
        DB::table('OPTV2USER')->insert([
            'id' => 1, 'USERNAME' => 'TEST.USER', 'PASSWORD' => 'old', 'ACTIVE' => '1',
        ]);
    }

    public function test_warn_mode_shows_a_dismissible_banner_instead_of_blocking()
    {
        config(['password_policy.mode' => 'warn', 'password_policy.notice_until' => null]);
        \Illuminate\Support\Facades\Route::middleware('web')->get('/password-warn-probe', function () {
            return response('Allowed');
        });

        $this->withSession(['staff' => 1])->get('/password-warn-probe')
            ->assertOk()->assertSee('Allowed')->assertSessionHas('staff');

        $html = view('partials.password_notice', ['passwordChangeNotice' => ['deadline' => null]])->render();
        $this->assertStringContainsString('alert-soft-warning', $html);
        $this->assertStringContainsString('alert-dismissible', $html);
        $this->assertStringContainsString('data-bs-dismiss="alert"', $html);
        $this->assertStringContainsString('btn-warning', $html);
        $this->assertStringContainsString(route('password.edit'), $html);
    }

    public function test_warn_mode_enforces_after_the_deadline_and_renders_it()
    {
        $html = view('partials.password_notice', ['passwordChangeNotice' => ['deadline' => '2026-11-05 00:00:00']])->render();
        $this->assertStringContainsString('5 Nov 2026', $html);

        config(['password_policy.mode' => 'warn', 'password_policy.notice_until' => now()->subMinute()->toDateTimeString()]);
        \Illuminate\Support\Facades\Route::middleware('web')->get('/password-warn-probe', function () {
            return response('Allowed');
        });
        $this->withSession(['staff' => 1])->get('/password-warn-probe')->assertRedirect(route('password.edit'));
    }

    public function test_policy_matches_the_reference_and_is_configurable()
    {
        $policy = app(PasswordPolicy::class);
        foreach (['short', '123456789012', '!!!!!!!!!!!!'] as $invalid) {
            $this->assertNotEmpty($policy->errors($invalid));
        }
        $this->assertSame([], $policy->errors('abcdefghijkl'));
        $this->assertSame([], $policy->errors('!12345678901'));
        config(['password_policy.minimum_length' => 14]);
        $this->assertNotEmpty($policy->errors('abcdefghijkl'));
    }

    public function test_login_and_existing_sessions_require_change()
    {
        $this->post('/authenticate/login', ['username' => 'TEST.USER', 'password' => 'old'])
            ->assertRedirect(route('password.edit'));
        $this->withSession(['staff' => 1])->get('/')->assertRedirect(route('password.edit'));
        $this->withSession(['staff' => 1])->getJson('/get_mainprojection_list')->assertStatus(403);
    }

    public function test_token_login_cannot_bypass_policy_or_run_projection_updates()
    {
        $this->get('/modules/createprojection?tk=B1example')->assertRedirect(route('password.edit'));
    }

    public function test_form_is_private_and_renders_in_reference_order()
    {
        $this->get('/change-password')->assertRedirect(route('login_page'));
        $response = $this->withSession(['staff' => 1])->get('/change-password');
        $response->assertOk()
            ->assertSeeInOrder(['Sign out', 'Change password', 'Change password for TEST.USER', 'CURRENT PASSWORD', 'NEW PASSWORD',
                'Use at least 8 characters.', 'Do not start with a number.', 'CONFIRM NEW PASSWORD', 'Save password', 'Back', 'Advisory'])
            ->assertSee('id="password-save"', false)
            ->assertSee('id="password-loading"', false);
        // Only the notice may sign the user out; the form itself must not.
        $this->assertSame(1, substr_count($response->getContent(), 'form="password-exit"'));
    }

    public function test_minimum_length_is_eight_and_guests_are_redirected_to_login()
    {
        $policy = app(PasswordPolicy::class);
        $this->assertSame([], $policy->errors('Abcdefg1'));
        $this->assertNotEmpty($policy->errors('Abcdef1'));
        $this->post('/change-password', [
            'current_password' => 'old', 'password' => 'Abcdefg1', 'password_confirmation' => 'Abcdefg1',
        ])->assertRedirect(route('login_page'));
        $this->assertSame('old', DB::table('OPTV2USER')->value('PASSWORD'));
    }

    public function test_invalid_input_never_updates_or_flashes_passwords()
    {
        $this->withSession(['staff' => 1])->post('/change-password', [
            'current_password' => 'wrong', 'password' => 'short', 'password_confirmation' => 'other',
        ])->assertSessionHasErrors(['current_password', 'password']);
        $this->get('/change-password')->assertOk()
            ->assertSee('is-invalid', false)
            ->assertSee('invalid-feedback', false)
            ->assertSee('The current password is incorrect.', false);
        $this->assertSame('old', DB::table('OPTV2USER')->value('PASSWORD'));
        $this->assertEmpty(session()->getOldInput());
    }

    public function test_change_updates_only_session_account_and_exit_logs_out()
    {
        DB::table('OPTV2USER')->insert(['id' => 2, 'USERNAME' => 'OTHER', 'PASSWORD' => 'unchanged', 'ACTIVE' => '1']);
        $this->withSession(['staff' => 1])->post('/change-password', [
            'id' => 2, 'current_password' => 'old', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertRedirect(route('dashboard_admin'));
        $this->get('/change-password')->assertDontSee('Sign out', false);
        $this->assertSame('NewPassword123', DB::table('OPTV2USER')->where('id', 1)->value('PASSWORD'));
        $this->assertSame('unchanged', DB::table('OPTV2USER')->where('id', 2)->value('PASSWORD'));
        $this->post('/change-password/exit')->assertRedirect(route('login_page'))->assertSessionMissing('staff');
    }

    public function test_server_rejects_bad_policy_and_confirmation_even_with_correct_current_password()
    {
        foreach ([['123456789012', '123456789012'], ['!!!!!!!!!!!!', '!!!!!!!!!!!!'], ['NewPassword123', 'Mismatch12345']] as $pair) {
            $this->withSession(['staff' => 1])->post('/change-password', [
                'current_password' => 'old', 'password' => $pair[0], 'password_confirmation' => $pair[1],
            ])->assertSessionHasErrors('password');
            $this->assertSame('old', DB::table('OPTV2USER')->value('PASSWORD'));
        }
    }

    public function test_current_password_spaces_are_preserved_and_disabled_accounts_cannot_change()
    {
        DB::table('OPTV2USER')->where('id', 1)->update(['PASSWORD' => ' old ']);
        $this->withSession(['staff' => 1])->post('/change-password', [
            'current_password' => 'old', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertSessionHasErrors('current_password');
        $this->withSession(['staff' => 1])->post('/change-password', [
            'current_password' => ' old ', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertRedirect(route('dashboard_admin'));
        DB::table('OPTV2USER')->where('id', 1)->update(['ACTIVE' => '0']);
        $this->get('/change-password')->assertRedirect(route('login_page'));
    }
}
