<?php

namespace App\Models;

use App\Policies\FlagPolicy;
use Database\Factories\FlagFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[UsePolicy(FlagPolicy::class)]
class Flag extends Model
{
    /** @use HasFactory<FlagFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'flaggable_id',
        'flaggable_type',
        'reason',
        'description',
        'resolved_at',
        'resolved_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created the flag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the flagged model (thread or reply).
     */
    public function flaggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the admin who resolved the flag.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
