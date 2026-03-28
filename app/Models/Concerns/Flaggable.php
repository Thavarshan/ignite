<?php

namespace App\Models\Concerns;

use App\Models\Flag;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Flaggable
{
    /**
     * Get all flags for the model.
     */
    public function flags(): MorphMany
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }

    /**
     * Determine if the given user has flagged this model.
     */
    public function flaggedBy(User $user): bool
    {
        return $this->flags()->where('user_id', $user->id)->exists();
    }
}
