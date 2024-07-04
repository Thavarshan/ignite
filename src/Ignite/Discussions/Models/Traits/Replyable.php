<?php

namespace Ignite\Discussions\Models\Traits;

use Ignite\Auth\Facades\Auth;
use Ignite\Discussions\Models\Reply;
use Ignite\Interfaces\Discussions\Body;
use Ignite\Interfaces\Users\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Replyable
{
    /**
     * Get all the replies for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function replies(): MorphMany
    {
        return $this->morphMany(Reply::class, 'replyable');
    }

    /**
     * Get the replies of the discussion.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    /**
     * Add a reply to the model.
     *
     * @param \Ignite\Interfaces\Discussions\Body $body
     * @param \Ignite\Interfaces\Users\User|null  $user
     *
     * @return \Ignite\Discussions\Models\Reply
     */
    public function addReply(Body $body, ?User $user = null): Reply
    {
        return $this->replies()->create([
            'user_id' => $user?->getId() ?? Auth::id(),
            'body' => $body->getContent(),
        ]);
    }

    /**
     * Get the number of replies for the model.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function repliesCount(): Attribute
    {
        return Attribute::make(fn ($value) => $this->replies()->count());
    }

    /**
     * Get the latest reply for the model.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getLatestReplyAttribute(): ?Model
    {
        return $this->replies->last();
    }
}
