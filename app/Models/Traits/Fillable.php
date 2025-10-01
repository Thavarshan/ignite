<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait Fillable
{
    /**
     * Filter and etract only data allowable to be changed.
     */
    public static function filterFillable(
        array $data,
        Model|string|null $resource = null
    ): array {
        if (is_null($resource)) {
            $resource = new self;
        }

        if (is_string($resource)) {
            $resource = new $resource;
        }

        return filter_attributes($data, $resource->getFillable());
    }
}
