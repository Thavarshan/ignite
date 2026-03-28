<?php

namespace App\Models;

use App\Models\Concerns\Flaggable;
use App\Models\Concerns\Likeable;
use App\Notifications\ReplyAdded;
use App\Notifications\ThreadReplied;
use App\Notifications\UserMentioned;
use App\Policies\ReplyPolicy;
use App\Services\MentionParser;
use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(ReplyPolicy::class)]
class Reply extends Model
{
    /** @use HasFactory<\Database\Factories\ReplyFactory> */
    use Filterable, Flaggable, HasFactory, Likeable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'thread_id',
        'user_id',
        'parent_id',
        'body',
        'likes_count',
    ];

    /**
     * Register model event callbacks.
     */
    protected static function booted(): void
    {
        static::created(function (Reply $reply): void {
            $reply->thread->incrementRepliesCount();
            $reply->thread->subscribe($reply->user);

            // Notify thread subscribers (excluding the replier)
            $reply->thread->subscriptions()
                ->where('user_id', '!=', $reply->user_id)
                ->with('user')
                ->get()
                ->each(fn ($sub) => $sub->user->notify(new ThreadReplied($reply->thread, $reply)));

            // Notify parent reply author for nested replies
            if ($reply->parent_id && $reply->parent->user_id !== $reply->user_id) {
                $reply->parent->user->notify(new ReplyAdded($reply->parent, $reply));
            }

            // Notify mentioned users
            $mentioned = (new MentionParser)->parse($reply->body);
            $mentioned->reject(fn (User $u) => $u->id === $reply->user_id)
                ->each(fn (User $u) => $u->notify(new UserMentioned($reply, $reply->user->name)));
        });

        static::deleted(function (Reply $reply): void {
            $reply->thread->decrementRepliesCount();
        });
    }

    /**
     * Get the thread this reply belongs to.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the user who authored the reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reply (for nested replies).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Reply::class, 'parent_id');
    }

    /**
     * Get the child replies nested under this reply.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Reply::class, 'parent_id');
    }
}
