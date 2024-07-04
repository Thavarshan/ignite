<?php

namespace Ignite\Statuses\Models;

use Ignite\Interfaces\Shared\Sluggable as SluggableInterface;
use Ignite\Shared\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

class Status extends Model implements SluggableInterface, AuditableInterface
{
    use Sluggable;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * The string representation of the model.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Get all of the owning statusable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }
}
