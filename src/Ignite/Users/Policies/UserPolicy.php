<?php

namespace Ignite\Users\Policies;

use Ignite\Auth\Enums\Role;
use Ignite\Auth\Policies\Policy;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class UserPolicy extends Policy
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
            $this->do(Action::READ, on: Resource::USERS)
        ) && $user->hasPermissionTo(
            $this->do(Action::CREATE, on: Resource::USERS)
        );
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Users\Models\User $model
     *
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        // If tthe user is the owner of the model, they can view it
        if ($user->is($model)) {
            return true;
        }

        // Special case for the NW staff to be given access to view any user
        if (
            $user->hasRole(Role::STAFF->value)
            && $user->hasPermissionTo(
                $this->do(Action::READ, on: Resource::USERS)
            )
        ) {
            return true;
        }

        // Otherwise, they have to have an elevated permission and belong to the same organisation
        return $user->belongsToAssociation($model->getOrganisation())
            && $user->hasPermissionTo(
                $this->do(Action::READ, on: Resource::USERS)
            );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \Ignite\Users\Models\User|null $user
     *
     * @return bool
     */
    public function create(?User $user): bool
    {
        if (is_null($user)) {
            return true; // anyone can register
        }

        // only admins can create users
        return $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::USERS)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Users\Models\User $model
     *
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        // Only the owner of the model can update it
        return $user->is($model) && $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::USERS)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Users\Models\User $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        // Either users to whom the model belongs to or users with elevated permissions
        // and belong to the same organisation can delete the model
        return $user->is($model)
            || ($user->belongsToAssociation($model->getOrganisation())
            && $user->hasPermissionTo(
                $this->do(Action::DELETE, on: Resource::USERS)
            ));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Users\Models\User $model
     *
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Users\Models\User $model
     *
     * @return bool
     */
    public function reinstate(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }
}
