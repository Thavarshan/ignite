<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use App\Observers\ThreadObserver;
use Filterable\Interfaces\Filterable as FilterableInterface;
use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[ObservedBy(ThreadObserver::class)]
class Thread extends Model implements FilterableInterface
{
    use Filterable, HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'is_ticket',
        'status',
        'priority',
        'assigned_to_id',
        'user_id',
        'channel_id',
        'slug',
        'views',
        'replies_count',
        'last_reply_at',
        'locked_at',
        'pinned_at',
        'flagged_at',
    ];

    /**
     * The attributes that should be used to generate the slug.
     *
     * @var array
     */
    protected $sluggableAttribute = 'title';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_ticket' => 'boolean',
            'last_reply_at' => 'datetime',
            'locked_at' => 'datetime',
            'pinned_at' => 'datetime',
            'flagged_at' => 'datetime',
            'status' => Status::class,
            'priority' => Priority::class,
        ];
    }

    /**
     * Get the user that created the thread.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that the thread is assigned to.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    /**
     * Get the channel that the thread belongs to.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Determine if the thread is a ticket.
     */
    public function isTicket(): bool
    {
        return (bool) $this->is_ticket;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
