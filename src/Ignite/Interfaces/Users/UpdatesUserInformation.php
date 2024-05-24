<?php

namespace Ignite\Interfaces\Users;

interface UpdatesUserInformation
{
    /**
     * Update the given user's profile information.
     *
     * @param \Ignite\Interfaces\Users\User $user
     * @param array<string, string>         $data
     * @param array<string, mixed>|null     $options
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function update(User $user, array $data, ?array $options = []): User;
}
