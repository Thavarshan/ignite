<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Resource;
use App\Models\Thread;
use App\Models\User;

class ThreadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::READ->value.':'.Resource::THREAD->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Thread $thread): bool
    {
        return $user->can(Permission::READ->value.':'.Resource::THREAD->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE->value.':'.Resource::THREAD->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Thread $thread): bool
    {
        return $user->can(Permission::UPDATE->value.':'.Resource::THREAD->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Thread $thread): bool
    {
        return $user->can(Permission::DELETE->value.':'.Resource::THREAD->value);
    }
}
