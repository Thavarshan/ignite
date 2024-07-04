<?php

namespace Ignite\Discussions\Policies;

use Ignite\Auth\Policies\Policy;
use Ignite\Discussions\Models\Discussion;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class DiscussionPolicy extends Policy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User        $user
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return bool
     */
    public function view(User $user, Discussion $discussion): bool
    {
        return ($user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::REPLIES)
        ) && $discussion->involves($user)) || $user->isStaff();
    }
}
