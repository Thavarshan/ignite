<?php

namespace Ignite\Interfaces\Users;

interface DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function delete(User $user): void;
}
