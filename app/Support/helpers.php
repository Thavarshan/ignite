<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

if (! function_exists('has_attribute')) {
    /**
     * Determine if the model table has given column.
     */
    function has_attribute(Model $model, string $column): bool
    {
        return Schema::hasColumn($model->getTable(), $column);
    }
}
