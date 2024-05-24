<?php

namespace Ignite\Shared\Data\Services\Traits;

use Ignite\Auth\Enums\Role;
use Ignite\Interfaces\Shared\Filter;
use Ignite\Interfaces\Users\User;

trait FilterApplicator
{
    /**
     * Apply the scoped filter to the user listing.
     *
     * @param \Ignite\Interfaces\Shared\Filter   $filter
     * @param \Ignite\Interfaces\Users\User|null $user
     *
     * @return \Ignite\Interfaces\Shared\Filter
     */
    protected function applyScopedFilter(
        Filter $filter,
        ?User $user = null
    ): Filter {
        return tap($filter, function (Filter $filter) use ($user) {
            if (! is_null($user) && ! $user->hasRole(Role::ADMIN->value)) {
                $filter->forUser($user);
            }
        });
    }
}
