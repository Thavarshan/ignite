<?php

namespace App\Interfaces\Watchers;

use Ignite\Interfaces\Shared\Entity;
use Ignite\Interfaces\Shared\Filterable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

interface WatcherEntry extends Entity, Filterable, Arrayable, JsonSerializable, Jsonable // phpcs:ignore
{
    /**
     * Create and store a new watcher entry.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Shared\Entity
     */
    public function store(array $data): Model|Entity;
}
