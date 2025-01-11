<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Resource;
use App\Models\Reply;
use App\Models\User;

class ReplyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::READ->value.':'.Resource::REPLY->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reply $reply): bool
    {
        return $user->can(Permission::READ->value.':'.Resource::REPLY->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE->value.':'.Resource::REPLY->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->can(Permission::UPDATE->value.':'.Resource::REPLY->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reply $reply): bool
    {
        return $user->can(Permission::DELETE->value.':'.Resource::REPLY->value);
    }
}
