<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\OPTv2User;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    private function user(Request $request)
    {
        return $request->session()->has('staff')
            ? OPTv2User::where('id', $request->session()->get('staff'))->where('ACTIVE', '1')->first()
            : null;
    }

    public function edit(Request $request, PasswordPolicy $policy)
    {
        $user = $this->user($request);
        if (!$user) {
            return redirect()->route('login_page');
        }
        return view('auth.change_password', [
            'username' => trim($user->USERNAME), 'rules' => $policy->rules(),
            'requiredChange' => count($policy->errors((string) $user->PASSWORD)) > 0,
        ]);
    }

    public function update(ChangePasswordRequest $request)
    {
        $user = $request->account();
        if (!$user) {
            return redirect()->route('login_page');
        }
        $validated = $request->validated();
        // Keep compatibility with the existing shared OPT login. Never accept an account ID from the form.
        $updated = OPTv2User::where('id', $user->id)->where('PASSWORD', $user->PASSWORD)
            ->update(['PASSWORD' => $validated['password']]);
        if (!$updated) {
            return redirect()->route('password.edit')->withErrors(['current_password' => 'Your account changed. Please sign in again.']);
        }
        $request->session()->forget('password_policy_check');
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard_admin');
    }

    public function exit(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login_page');
    }
}
