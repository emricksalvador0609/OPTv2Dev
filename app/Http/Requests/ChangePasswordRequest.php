<?php

namespace App\Http\Requests;

use App\Models\OPTv2User;
use App\Support\PasswordPolicy;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    private ?OPTv2User $account = null;

    private bool $accountResolved = false;

    /**
     * The active account for the current session, or null when none applies.
     */
    public function account(): ?OPTv2User
    {
        if (!$this->accountResolved) {
            $this->accountResolved = true;
            $staff = $this->session()->get('staff');
            $this->account = $staff
                ? OPTv2User::where('id', $staff)->where('ACTIVE', '1')->first()
                : null;
        }

        return $this->account;
    }

    public function authorize(): bool
    {
        return $this->account() !== null;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(redirect()->route('login_page'));
    }

    public function rules(PasswordPolicy $policy): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password',
                function ($attribute, $value, $fail) use ($policy) {
                    foreach ($policy->errors((string) $value) as $error) {
                        $fail($error);
                    }
                },
            ],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $account = $this->account();
            if (!$account) {
                return;
            }
            // The shared OPT login stores plain passwords; keep comparing exactly as login does.
            if (!is_string($this->input('current_password')) ||
                !hash_equals((string) $account->PASSWORD, (string) $this->input('current_password'))) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });
    }
}
