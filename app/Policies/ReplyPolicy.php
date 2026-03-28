<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;

class ReplyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reply $reply): bool
    {
        return true;
    }

    /**
     * Gate passes the Thread directly as the second argument when using
     * $user->can('create', [Reply::class, $thread]).
     */
    public function create(User $user, Thread $thread): bool
    {
        if ($thread->locked) {
            return $user->isAdmin();
        }

        return true;
    }

    public function update(User $user, Reply $reply): bool
    {
        return $user->isAdmin() || $user->id === $reply->user_id;
    }

    public function delete(User $user, Reply $reply): bool
    {
        return $user->isAdmin() || $user->id === $reply->user_id;
    }

    public function restore(User $user, Reply $reply): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Reply $reply): bool
    {
        return $user->isAdmin();
    }
}
