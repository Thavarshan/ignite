<?php

namespace App\Models;

use App\Models\Concerns\Flaggable;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Likeable;
use App\Models\Concerns\Subscribable;
use App\Policies\ThreadPolicy;
use Database\Factories\ThreadFactory;
use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(ThreadPolicy::class)]
class Thread extends Model
{
    /** @use HasFactory<ThreadFactory> */
    use Filterable, Flaggable, HasFactory, HasSlug, Likeable, SoftDeletes, Subscribable;

    /**
     * The model's default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'locked' => false,
        'pinned' => false,
        'replies_count' => 0,
        'visits' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'channel_id',
        'user_id',
        'title',
        'slug',
        'body',
        'locked',
        'pinned',
        'replies_count',
        'best_reply_id',
        'likes_count',
        'visits',
        'last_posted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'locked' => 'bool',
            'pinned' => 'bool',
            'last_posted_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the user that owns the thread.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the channel this thread belongs to.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get all replies for the thread.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Get the reply marked as the best answer.
     */
    public function bestReply(): BelongsTo
    {
        return $this->belongsTo(Reply::class, 'best_reply_id');
    }

    /**
     * Mark a reply as the best answer for this thread.
     */
    public function markBestReply(Reply $reply): void
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    /**
     * Remove the best answer designation from this thread.
     */
    public function unmarkBestReply(): void
    {
        $this->update(['best_reply_id' => null]);
    }

    /**
     * Atomically increment the visit counter.
     */
    public function recordVisit(): static
    {
        $this->increment('visits');

        return $this;
    }

    /**
     * Atomically increment the reply counter and update last activity.
     * Called by the Reply model observer when a reply is created.
     */
    public function incrementRepliesCount(): static
    {
        $this->increment('replies_count');
        $this->update(['last_posted_at' => now()]);

        return $this;
    }

    /**
     * Atomically decrement the reply counter.
     * Called by the Reply model observer when a reply is deleted.
     */
    public function decrementRepliesCount(): static
    {
        $this->decrement('replies_count');

        return $this;
    }
}
