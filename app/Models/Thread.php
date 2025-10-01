<?php

namespace App\Models;

use App\Models\Traits\Fillable;
use App\Models\Traits\HasSlug;
use Filterable\Contracts\Filterable;
use Filterable\Traits\Filterable as HasFilters;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model implements Filterable
{
    /** @use HasFactory<\Database\Factories\ThreadFactory> */
    use Fillable, HasFactory, HasFilters, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'channel_id',
        'user_id',
        'locked_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'locked',
        'replies_count',
    ];

    /**
     * Get the channel that owns the thread.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get the author that owns the thread.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the replies for the thread.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Add a reply to the thread.
     */
    public function addReply(array $attributes): Reply
    {
        return $this->replies()->create($attributes);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'locked_at' => 'datetime',
        ];
    }

    /**
     * Get the replies count attribute.
     */
    public function repliesCount(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->replies()->count(),
        );
    }

    /**
     * Determine if this thread is locked.
     */
    public function locked(): Attribute
    {
        return Attribute::make(fn (): bool => ! is_null($this->locked_at));
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
