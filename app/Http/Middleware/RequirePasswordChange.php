<?php

namespace App\Http\Middleware;

use App\Models\OPTv2User;
use App\Support\PasswordPolicy;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('staff') || $request->routeIs(
            'password.edit', 'password.update', 'password.exit', 'logoff_admin'
        )) {
            return $next($request);
        }

        $identity = (string) $request->session()->get('staff');
        $policyVersion = hash('sha256', json_encode(config('password_policy')));
        $check = $request->session()->get('password_policy_check');
        if (!is_array($check) || ($check['identity'] ?? null) !== $identity ||
            ($check['version'] ?? null) !== $policyVersion || ($check['expires'] ?? 0) <= time()) {
            $user = OPTv2User::select('id', 'ACTIVE', 'PASSWORD')->find($identity);
            if (!$user || trim((string) $user->ACTIVE) !== '1') {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login_page');
            }
            $check = [
                'identity' => $identity,
                'version' => $policyVersion,
                'required' => count(app(PasswordPolicy::class)->errors((string) $user->PASSWORD)) > 0,
                'expires' => time() + max(1, (int) config('password_policy.recheck_seconds', 300)),
            ];
            // Cache only the decision, never the password, in this user's server-side session.
            $request->session()->put('password_policy_check', $check);
        }
        if ($check['required']) {
            if (!$this->enforced()) {
                // Warn mode: let the session continue and let the layout show a dismissible notice.
                view()->share('passwordChangeNotice', [
                    'deadline' => config('password_policy.notice_until'),
                ]);
                return $next($request);
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'code' => 'PASSWORD_CHANGE_REQUIRED',
                    'message' => 'Please change your password before continuing.',
                    'redirect' => route('password.edit'),
                ], 403);
            }
            return redirect()->route('password.edit');
        }
        return $next($request);
    }

    /**
     * Enforcement is off in warn mode until the configured deadline passes.
     */
    private function enforced(): bool
    {
        if (config('password_policy.mode', 'warn') === 'enforce') {
            return true;
        }

        $until = config('password_policy.notice_until');
        if (empty($until)) {
            return false;
        }

        try {
            return now()->greaterThanOrEqualTo(Carbon::parse($until));
        } catch (\Exception $e) {
            // A malformed deadline must not block people; stay in warn mode.
            return false;
        }
    }
}
