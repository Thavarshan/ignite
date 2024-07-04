<?php

namespace Ignite\Settings\Models;

use Ignite\Activities\Enums\Event;
use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Interfaces\Settings\Settings as SettingsInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Auditable;

/**
 * @property int            $id
 * @property array          $items
 * @property string         $subject_type
 * @property int            $subject_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Settings extends Model implements SettingsInterface
{
    use HasFactory;
    use Entity;
    use Trackable;
    use Filterable;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'items',
        'subject_type',
        'subject_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Get the subject of the Settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'subject_type', 'subject_id');
    }

    /**
     * Get the settings items.
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items ?? [];
    }

    /**
     * Define the events that should be recorded.
     *
     * @return array<string>
     */
    public static function defineTrackableEvents(): array
    {
        return [Event::UPDATED->value];
    }
}
