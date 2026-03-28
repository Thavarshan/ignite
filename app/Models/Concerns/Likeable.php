<?php

namespace App\Models\Concerns;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
    /**
     * Boot the trait.
     */
    public static function bootLikeable(): void
    {
        static::created(function ($model): void {
            // Ensure likes_count starts at 0
        });
    }

    /**
     * Get all likes for the model.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Determine if the given user has liked this model.
     */
    public function likedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Record a like from the given user.
     */
    public function like(User $user): Like
    {
        $like = $this->likes()->create(['user_id' => $user->id]);
        $this->increment('likes_count');

        return $like;
    }

    /**
     * Remove a like from the given user.
     */
    public function unlike(User $user): void
    {
        $deleted = $this->likes()->where('user_id', $user->id)->delete();

        if ($deleted > 0) {
            $this->decrement('likes_count');
        }
    }
}
