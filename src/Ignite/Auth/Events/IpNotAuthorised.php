<?php

namespace Ignite\Auth\Events;

use Closure;
use Ignite\Interfaces\Users\User;

class IpNotAuthorised extends AuthEvent
{
    /**
     * Create a new event instance.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    public function __construct(protected User $user)
    {
    }

    /**
     * Get the user instance.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the related organisation.
     *
     * @return mixed
     */
    public function relatedOrganisation(Closure $callback): mixed
    {
        return $callback($this->user->getOrganisation());
    }
}
