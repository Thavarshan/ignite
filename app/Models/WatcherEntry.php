<?php

namespace App\Models;

use App\Interfaces\Watchers\WatcherEntry as WatcherEntryInterface;
use App\Support\InteractsWithContainer;
use App\Support\Loggable;
use App\ValueObjects\RuntimeKey;
use Ignite\Interfaces\Shared\Entity as EntityInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Fillable;
use Ignite\Shared\Data\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * WatcherEntry is to primarily be used for logging application events and actions.
 *
 * @property string|null    $uid
 * @property string         $batch_id
 * @property string|null    $family_hash
 * @property bool           $should_display_on_index
 * @property string         $type
 * @property object         $content
 * @property \Carbon\Carbon $created_at
 */
class WatcherEntry extends Model implements WatcherEntryInterface
{
    use InteractsWithContainer;
    use Entity;
    use Filterable;
    use HasFactory;
    use Fillable;
    use Loggable;

    /**
     * The name of the "updated at" column.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'batch_id',
        'family_hash',
        'should_display_on_index',
        'type',
        'content',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'object',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'watcher_entries';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Get the content attribute.
     *
     * @return object
     */
    public function getContent(): object
    {
        return $this->content;
    }

    /**
     * Create and store a new watcher entry.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function store(array $data): Model|EntityInterface
    {
        $this->fill(array_merge([
            'uid' => $this->resolve(RuntimeKey::class)->value(),
        ], $this->filterFillable($data, $this)));

        try {
            $this->saveQuietly();
        } catch (Throwable $e) {
            $this->logError($e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return $this;
    }
}
