<?php

namespace App\Models\Concerns;

use App\Models\ThreadSubscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Subscribable
{
    /**
     * Get all subscriptions for the model.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Subscribe a user to this model.
     */
    public function subscribe(User $user): ThreadSubscription
    {
        return $this->subscriptions()->firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * Unsubscribe a user from this model.
     */
    public function unsubscribe(User $user): void
    {
        $this->subscriptions()->where('user_id', $user->id)->delete();
    }

    /**
     * Determine if a user is subscribed to this model.
     */
    public function isSubscribedTo(User $user): bool
    {
        return $this->subscriptions()->where('user_id', $user->id)->exists();
    }
}
