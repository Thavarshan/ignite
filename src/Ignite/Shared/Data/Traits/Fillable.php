<?php

namespace Ignite\Shared\Data\Traits;

use Ignite\Interfaces\Shared\Entity;
use Illuminate\Database\Eloquent\Model;

trait Fillable
{
    /**
     * Filter and etract only data allowable to be changed.
     *
     * @param \Ignite\Interfaces\Shared\Entity|\Illuminate\Database\Eloquent\Model|string|null $resource
     *
     * @return array
     */
    public function filterFillable(
        array $data,
        Entity|Model|string|null $resource = null
    ): array {
        if (is_null($resource)) {
            $resource = $this;
        }

        if (is_string($resource)) {
            $resource = new $resource();
        }

        return filter_attributes($data, $resource->getFillable());
    }
}
