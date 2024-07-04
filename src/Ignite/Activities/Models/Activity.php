<?php

namespace Ignite\Activities\Models;

use Ignite\Interfaces\Activities\Activity as ActivityInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Activity is to primarily be used for logging user actions.
 *
 * @property int            $id
 * @property string         $event
 * @property array          $changes
 * @property int            $user_id
 * @property string         $subject_type
 * @property int            $subject_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Activity extends Model implements ActivityInterface
{
    use HasFactory;
    use Entity;
    use Filterable;

    /**
     * The attributes that should be cast.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'changes',
        'subject_type',
        'subject_id',
        'started_at',
        'ended_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the subject of the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'subject_type', 'subject_id');
    }

    /**
     * Get the user who triggered the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
