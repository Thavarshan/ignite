<?php

namespace Ignite\Notes\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Categories\Models\Traits\Categorisable;
use Ignite\Interfaces\Notes\Notable;
use Ignite\Interfaces\Notes\Note as NoteInterface;
use Ignite\Interfaces\Shared\HasPath;
use Ignite\Notes\Observers\NoteObserver;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;

/**
 * @property int                                                                          $id
 * @property string                                                                       $title
 * @property string                                                                       $body
 * @property int                                                                          $user_id
 * @property int                                                                          $notable_id
 * @property string                                                                       $notable_type
 * @property string                                                                       $user_name
 * @property \Carbon\Carbon                                                               $created_at
 * @property \Carbon\Carbon                                                               $updated_at
 * @property \Carbon\Carbon                                                               $deleted_at
 * @property \Ignite\Interfaces\Users\User                                                $user
 * @property \Ignite\Interfaces\Notes\Notable                                             $notable
 * @property \Illuminate\Database\Eloquent\Collection<\Ignite\Categories\Models\Category> $categories
 */
#[ObservedBy(NoteObserver::class)]
class Note extends Model implements NoteInterface
{
    use HasFactory;
    use Entity;
    use Trackable;
    use Searchable;
    use SoftDeletes;
    use Categorisable;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'notable_id',
        'notable_type',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'user_name',
    ];

    /**
     * Get the user that owns the note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning notable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the model that the note belongs to.
     *
     * @return \Ignite\Interfaces\Notes\Notable
     */
    public function getNotable(): Notable
    {
        return $this->notable;
    }

    /**
     * Get the user name attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function userName(): Attribute
    {
        return Attribute::make(fn () => $this->getUserName());
    }

    /**
     * Get the user name.
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user->getName();
    }

    /**
     * Get the title of the note.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the body of the note.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the full URL path to the entity.
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->notable instanceof HasPath
            ? $this->notable->getPath()
            : null;
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
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
