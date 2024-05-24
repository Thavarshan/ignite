<?php

namespace Ignite\Interfaces\Auth;

interface Authenticator
{
    /**
     * Attempt to authenticate a user.
     *
     * @param string $email
     * @param string $password
     *
     * @return mixed
     */
    public function login(string $email, string $password): mixed;

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void;
}
