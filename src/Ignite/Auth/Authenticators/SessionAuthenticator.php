<?php

namespace Ignite\Auth\Authenticators;

use Ignite\Interfaces\Auth\Authenticator;

class SessionAuthenticator implements Authenticator
{
    /**
     * Attempt to authenticate a user.
     *
     * @param string $email
     * @param string $password
     *
     * @return mixed
     */
    public function login(string $email, string $password): mixed
    {
        return [];
    }

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void
    {
        //
    }
}
