<?php

namespace App\Http\Requests\Traits;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;

trait ValidatesUserCredentials
{
    /**
     * Set custom validator function to validate user input password.
     *
     * @param string $inputName
     *
     * @return Closure
     */
    protected function validatePassword(
        string $inputName = 'password'
    ): Closure {
        return function (ValidatorInterface $validator) use ($inputName) {
            if (
                ! $this->validateUserCredentials(['password' => $this->{$inputName}])
            ) {
                $validator->errors()->add(
                    $inputName,
                    __('The provided password does not match your current password.')
                );
            }
        };
    }

    /**
     * Validate currently authenticated user's credentials.
     *
     * @param array $credentials
     *
     * @return bool
     */
    protected function validateUserCredentials(array $credentials): bool
    {
        return app()->make(StatefulGuard::class)
            ->getProvider()
            ->validateCredentials($this->user(), $credentials);
    }
}
