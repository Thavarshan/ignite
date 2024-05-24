<?php

namespace Ignite\Users\Repositories;

use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserRepository as UserRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class UserRepository extends Repository implements UserRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
