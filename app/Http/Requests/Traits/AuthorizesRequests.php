<?php

namespace App\Http\Requests\Traits;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use NetWatch\Auth\Facades\Auth;

trait AuthorizesRequests
{
    /**
     * Determine if the user making the request is a guest.
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return is_null($this->user());
    }

    /**
     * Determine if the user making the request is authenticated and is the currently authenticated user.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return with($this->user(), function (?User $user) {
            if ($this->route()->hasParameter('user')) {
                return $user->is($this->route('user')) && ! is_null($user);
            }

            return ! is_null($user);
        });
    }

    /**
     * Determine if the user making the request is authorized to perform given ability on resource.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     * @param bool        $withoutAuthentication
     *
     * @return bool
     */
    public function isAllowed(
        string $ability,
        mixed $arguments = [],
        bool $withoutAuthentication = true
    ): bool {
        if ($this->isAuthenticated() || $withoutAuthentication) {
            return Gate::allows($ability, $arguments);
        }

        return false;
    }

    /**
     * Determine if a user with given email address exists in the database.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        $field = Auth::username('email');

        return User::where($field, $this->input($field))->exists();
    }

    /**
     * Determine if all of the given abilities should be granted for the current user.
     *
     * @param iterable|string $abilities
     * @param array|mixed     $arguments
     *
     * @return bool
     */
    public function check(
        iterable|string $abilities,
        mixed $arguments = []
    ): bool {
        return Gate::check($abilities, $arguments);
    }
}
