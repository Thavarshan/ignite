<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\Resource;
use App\Models\Channel;
use App\Models\User;

class ChannelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::READ->value . ':' . Resource::CHANNEL->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Channel $channel): bool
    {
        return $user->can(Permission::READ->value . ':' . Resource::CHANNEL->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CREATE->value . ':' . Resource::CHANNEL->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Channel $channel): bool
    {
        return $user->can(Permission::UPDATE->value . ':' . Resource::CHANNEL->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Channel $channel): bool
    {
        return $user->can(Permission::DELETE->value . ':' . Resource::CHANNEL->value);
    }
}
