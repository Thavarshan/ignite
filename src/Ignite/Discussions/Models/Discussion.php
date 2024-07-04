<?php

namespace Ignite\Discussions\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Categories\Models\Traits\Categorisable;
use Ignite\Discussions\Models\Traits\Replyable;
use Ignite\Interfaces\Discussions\Discussion as DiscussionInterface;
use Ignite\Interfaces\Discussions\Reply;
use Ignite\Interfaces\Users\User;
use Ignite\Organisations\Models\Traits\BelongsToOrganisation;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Enums\Attribute;
use Ignite\Shared\Traits\Hashable;
use Ignite\Shared\Traits\Lockable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;

/**
 * @property int                                             $id
 * @property string                                          $title
 * @property string                                          $body
 * @property string                                          $discussable_type
 * @property int                                             $discussable_id
 * @property int                                             $team_id
 * @property int                                             $organisation_id
 * @property \Carbon\Carbon                                  $locked_at
 * @property \Carbon\Carbon                                  $created_at
 * @property \Carbon\Carbon                                  $updated_at
 * @property \Illuminate\Database\Eloquent\Collection        $replies
 * @property \Illuminate\Database\Eloquent\Relations\MorphTo $discussable
 */
class Discussion extends Model implements DiscussionInterface
{
    use HasFactory;
    use Entity;
    use Replyable;
    use Trackable;
    use Filterable;
    use Lockable;
    use Categorisable;
    use Hashable;
    use BelongsToOrganisation;
    use Auditable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'discussable_type',
        'discussable_id',
        'team_id',
        'organisation_id',
        'locked_at',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = [
        'replies',
    ];

    /**
     * Get the model that the discussion belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function discussable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return Attribute::UID->value;
    }

    /**
     * Get the title of the discussion.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    /**
     * Get the body of the discussion.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->getAttribute('body');
    }

    /**
     * Get the team ID of the discussion.
     *
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->getAttribute('team_id');
    }

    /**
     * Determine if the discussion involves the given user.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return bool
     */
    public function involves(User $user): bool
    {
        return $this->replies->contains(
            fn (Reply $reply) => $reply->isMadeBy($user)
        ) || $this->discussable->involves($user);
    }

    /**
     * Get the full URL path to the entity.
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return route('discussions.show', $this);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'discussable_type' => $this->discussable_type,
            'discussable_id' => $this->discussable_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
