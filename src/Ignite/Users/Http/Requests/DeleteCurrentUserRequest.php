<?php

namespace Ignite\Users\Http\Requests;

use Ignite\Auth\Facades\Auth;
use Ignite\Shared\Enums\Action;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

class DeleteCurrentUserRequest extends CurrentUserRequest
{
    /**
     * Indicate if the user confirmed their password.
     *
     * @var bool
     */
    protected $confirmed = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAllowed(
            Action::DELETE->value,
            $this->user(),
            withoutAuthentication: false
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Determine if the user confirmed their password.
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        tap(Auth::username('email'), function (string $username) {
            $confirmed = $this->make(StatefulGuard::class)->validate([
                $username => $this->authUser()->{$username},
                'password' => $this->input('password'),
            ]);

            $this->confirmed = $confirmed;

            if (! $confirmed) {
                throw ValidationException::withMessages([
                    'password' => __('The password is incorrect.'),
                ]);
            }
        });
    }
}
