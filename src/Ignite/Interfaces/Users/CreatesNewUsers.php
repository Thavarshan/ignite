<?php

namespace Ignite\Interfaces\Users;

interface CreatesNewUsers
{
    /**
     * Create a newly registered user.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function create(array $data, ?array $options = []): User;

    /**
     * Get the newly created user instance.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function getUser(): User;
}
