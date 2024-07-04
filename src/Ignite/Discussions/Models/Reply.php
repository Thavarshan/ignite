<?php

namespace Ignite\Discussions\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Discussions\Observers\ReplyObserver;
use Ignite\Interfaces\Discussions\Reply as ReplyInterface;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Materials\Traits\HasMedia;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Traits\Lockable;
use Ignite\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;

/**
 * @property int                       $id
 * @property int                       $user_id
 * @property string                    $body
 * @property int                       $replyable_id
 * @property string                    $replyable_type
 * @property \Carbon\Carbon            $locked_at
 * @property \Carbon\Carbon            $created_at
 * @property \Carbon\Carbon            $updated_at
 * @property \Ignite\Users\Models\User $user
 */
#[ObservedBy(ReplyObserver::class)]
class Reply extends Model implements ReplyInterface
{
    use HasFactory;
    use Entity;
    use Filterable;
    use Trackable;
    use Searchable;
    use Lockable;
    use HasMedia;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'body',
        'replyable_id',
        'replyable_type',
        'locked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'locked_at' => 'datetime',
    ];

    /**
     * Get the parent replyable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that created the reply.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who owns the reply.
     *
     * @return \Ignite\Interfaces\Users\User
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * Determine if the reply was made by the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     */
    public function isMadeBy(UserInterface $user): bool
    {
        return $this->user_id === $user->getId();
    }
}
