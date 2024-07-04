<?php

namespace Ignite\Auth\Policies;

use Ignite\Auth\Models\Role;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class RolePolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param \Ignite\Users\Models\User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::ROLES)
        );
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Auth\Models\Role  $role
     *
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::ROLES)
        );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \Ignite\Users\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::CREATE, on: Resource::ROLES)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Auth\Models\Role  $role
     *
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::ROLES)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Auth\Models\Role  $role
     *
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::DELETE, on: Resource::ROLES)
        );
    }
}
