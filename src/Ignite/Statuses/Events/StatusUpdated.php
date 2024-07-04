<?php

namespace Ignite\Statuses\Events;

use Ignite\Statuses\Models\Status;
use Illuminate\Database\Eloquent\Model;

class StatusUpdated
{
    /**
     * Create a new event instance.
     *
     * @param \Ignite\Statuses\Models\Status|null $oldStatus
     * @param \Ignite\Statuses\Models\Status      $newStatus
     * @param \Illuminate\Database\Eloquent\Model $statusable
     *
     * @return void
     */
    public function __construct(
        public ?Status $oldStatus,
        public Status $newStatus,
        public Model $statusable
    ) {
    }
}
