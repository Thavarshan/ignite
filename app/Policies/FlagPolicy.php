<?php

namespace App\Policies;

use App\Models\Flag;
use App\Models\User;

class FlagPolicy
{
    /**
     * Determine whether the user can view all flags.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create a flag.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can resolve a flag.
     */
    public function resolve(User $user, Flag $flag): bool
    {
        return $user->isAdmin();
    }
}
