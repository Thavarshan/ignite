<?php

namespace Ignite\Auth\Actions;

use Ignite\Interfaces\Auth\ResetsUserPasswords;
use Ignite\Interfaces\Users\User;

class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * Validate and reset the user's password.
     *
     * @param \Ignite\Interfaces\Users\User $user
     * @param array<string, mixed>          $data
     * @param array<string, mixed>|null     $options
     *
     * @return void
     */
    public function reset(User $user, array $data, ?array $options = []): void
    {
        $user->update(['password' => $data['password']]); // Password is hashed in the HasPassword trait.
    }
}
