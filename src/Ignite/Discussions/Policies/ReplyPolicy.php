<?php

namespace Ignite\Discussions\Policies;

use Ignite\Auth\Policies\Policy;
use Ignite\Discussions\Models\Reply;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class ReplyPolicy extends Policy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User        $user
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return bool
     */
    public function view(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::REPLIES)
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
            $this->do(Action::CREATE, on: Resource::REPLIES)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User        $user
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return bool
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->is($reply->user) && $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::REPLIES)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User        $user
     * @param \Ignite\Discussions\Models\Reply $reply
     */
    public function delete(User $user, Reply $reply): bool
    {
        return $user->is($reply->user) || $user->hasPermissionTo(
            $this->do(Action::DELETE, on: Resource::REPLIES)
        );
    }
}
