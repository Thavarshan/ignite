<?php

namespace Ignite\Auth\Actions;

use Ignite\Auth\Http\Requests\UnauthenticateRequest;
use Ignite\Interfaces\Auth\UnauthenticatesUsers;
use Illuminate\Contracts\Auth\StatefulGuard;

class UnauthenticateUser implements UnauthenticatesUsers
{
    /**
     * Create a new action instance.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     *
     * @return void
     */
    public function __construct(
        protected readonly StatefulGuard $guard
    ) {
    }

    /**
     * Attempt to unauthenticate a new session.
     *
     * @param \Ignite\Auth\Http\Requests\UnauthenticateRequest $request
     *
     * @return void
     */
    public function logout(UnauthenticateRequest $request): void
    {
        tap($this->guard->user(), function ($user) {
            /** @var \Ignite\Interfaces\Users\User $user */
            $user->fireLoggedOutEvent();
        });

        $this->guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
