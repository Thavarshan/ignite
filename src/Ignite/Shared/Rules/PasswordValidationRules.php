<?php

namespace Ignite\Shared\Rules;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @param array<int, \Illuminate\Contracts\Validation\Rule|array|string> $overrides
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules(array $overrides = []): array
    {
        return array_merge([
            in_array('nullable', $overrides) ? 'nullable' : 'required',
            'string',
            Password::default(),
            'confirmed',
        ], $overrides);
    }
}
