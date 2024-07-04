<?php

namespace Ignite\Auth\Policies;

use Ignite\Auth\Models\Permission;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class PermissionPolicy extends Policy
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
            $this->do(Action::READ, on: Resource::PERMISSIONS)
        );
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User      $user
     * @param \Ignite\Auth\Models\Permission $permission
     *
     * @return bool
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::PERMISSIONS)
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
            $this->do(Action::CREATE, on: Resource::PERMISSIONS)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User      $user
     * @param \Ignite\Auth\Models\Permission $permission
     *
     * @return bool
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::PERMISSIONS)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User      $user
     * @param \Ignite\Auth\Models\Permission $permission
     *
     * @return bool
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::DELETE, on: Resource::PERMISSIONS)
        );
    }
}
