<?php

namespace Ignite\Shared\Traits;

use Closure;
use Ignite\Interfaces\Users\User;

trait HasUser
{
    /**
     * Determine if the given user is on the model.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return bool
     */
    public function hasUser(User $user, ?Closure $callback = null): bool
    {
        $check = $this->users->contains($user);

        if (! is_null($callback)) {
            return $check || $callback($user);
        }

        return $check;
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     *
     * @param string $email
     *
     * @return bool
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->users
            ->contains(function (User $user) use ($email) {
                return $user->getEmail() === $email;
            });
    }
}
