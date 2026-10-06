<?php

namespace App\Support;

class PasswordPolicy
{
    public function rules(): array
    {
        $minimum = config('password_policy.minimum_length', 12);
        return array_merge([
            ['minimum' => $minimum, 'message' => "Use at least {$minimum} characters."],
        ], config('password_policy.patterns', []));
    }

    public function errors(string $password): array
    {
        $errors = [];
        foreach ($this->rules() as $rule) {
            $valid = isset($rule['minimum'])
                ? mb_strlen($password) >= $rule['minimum']
                : preg_match('~'.$rule['pattern'].'~u', $password) === 1;
            if (!$valid) {
                $errors[] = $rule['message'];
            }
        }
        if (strlen($password) > config('password_policy.maximum_bytes', 254)) {
            $errors[] = 'The password is too long.';
        }
        return $errors;
    }
}
