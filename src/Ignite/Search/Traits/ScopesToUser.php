<?php

namespace Ignite\Search\Traits;

use Ignite\Interfaces\Search\ScopesToUser as ScopesToUserInterface;
use Ignite\Interfaces\Users\User;

trait ScopesToUser
{
    /**
     * The user the search should be scoped to.
     *
     * @var \Ignite\Interfaces\Users\User|null
     */
    protected ?User $user = null;

    /**
     * Set the user the search should be scoped to.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Ignite\Interfaces\Search\ScopesToUser
     */
    public function scopeToUser(?User $user): ScopesToUserInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the user the search is scoped to.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
