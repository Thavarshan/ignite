<?php

namespace Ignite\Auth\DTO;

use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\Credentials as CredentialsInterface;
use Ignite\Shared\Data\DTO\DTO;

class Credentials extends DTO implements CredentialsInterface
{
    /**
     * Create a new credentials instance.
     *
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function __construct(
        protected string $username,
        protected string $password
    ) {
        parent::__construct([
            Auth::username() => $this->username,
            Auth::password() => $this->password,
        ]);
    }

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
