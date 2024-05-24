<?php

namespace Ignite\Users\Http\Requests;

use App\Http\Requests\Request;
use Ignite\Interfaces\Users\User;

class CurrentUserRequest extends Request
{
    /**
     * Get the currently authenticated user instance with relationship.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function authUser(): ?User
    {
        return tap($this->user(), function (?User $user) {
            return $user->load(['roles', 'teams', 'organisation']);
        });
    }
}
