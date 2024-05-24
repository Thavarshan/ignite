<?php

namespace Ignite\Users\Abstracts;

use Ignite\Interfaces\Users\User;

abstract class UserAbstract
{
    /**
     * Create a new event instance.
     *
     * @param \Ignite\Interfaces\Users\User|null $user
     *
     * @return void
     */
    public function __construct(protected ?User $user = null)
    {
    }

    /**
     * Set the user instance.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the user instance.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
