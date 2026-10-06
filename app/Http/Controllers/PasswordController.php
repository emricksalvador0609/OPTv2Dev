<?php

namespace App\Http\Controllers;

use App\Models\OPTv2User;
use App\Support\PasswordPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request, PasswordPolicy $policy)
    {
        $user = $this->user($request);
        if (!$user) {
            return redirect()->route('login_page');
        }
        $validator = Validator::make($request->only('current_password', 'password', 'password_confirmation'), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', 'different:current_password', function ($attribute, $value, $fail) use ($policy) {
                foreach ($policy->errors($value) as $error) {
                    $fail($error);
                }
            }],
            'password_confirmation' => ['required', 'string'],
        ]);
        $validator->after(function ($validator) use ($request, $user) {
            if (!is_string($request->input('current_password')) ||
                !hash_equals((string) $user->PASSWORD, $request->input('current_password'))) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });
        if ($validator->fails()) {
            return redirect()->route('password.edit')->withErrors($validator);
        }
        // Keep compatibility with the existing shared OPT login. Never accept an account ID from the form.
        $updated = OPTv2User::where('id', $user->id)->where('PASSWORD', $user->PASSWORD)
            ->update(['PASSWORD' => $request->input('password')]);
        if (!$updated) {
            return redirect()->route('password.edit')->withErrors(['current_password' => 'Your account changed. Please sign in again.']);
        }
        $request->session()->forget('password_policy_check');
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        return redirect()->route('password.edit')->with('password_updated', 'Your password has been updated.');
    }

    public function exit(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login_page');
    }
}
