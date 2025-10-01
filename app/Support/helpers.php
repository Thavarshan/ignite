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

if (! function_exists('filter_attributes')) {
    /**
     * Filter and etract only data allowable to be changed.
     *
     * @param  array<string>  $data
     * @param  array<string>  $attributes
     * @return array<string>
     */
    function filter_attributes(array $data, array $attributes): array
    {
        return array_filter(
            $data,
            function (string $key) use ($attributes): bool {
                return in_array($key, $attributes);
            },
            \ARRAY_FILTER_USE_KEY
        );
    }
}

if (! function_exists('filter_attributes_reverse')) {
    /**
     * Filter and etract only data allowable to be changed.
     *
     * @param  array<string>  $data
     * @param  array<string>  $attributes
     * @return array<string>
     */
    function filter_attributes_reverse(array $data, array $attributes): array
    {
        return array_filter(
            $data,
            function (string $key) use ($attributes): bool {
                return ! in_array($key, $attributes);
            },
            \ARRAY_FILTER_USE_KEY
        );
    }
}

if (! function_exists('reverse_filter_attributes')) {
    /**
     * Reverse filter and etract only data allowable to be changed.
     *
     * @param  array<string>  $data
     * @param  array<string>  $attributes
     * @return array<string>
     */
    function reverse_filter_attributes(array $data, array $attributes): array
    {
        return array_filter(
            $data,
            function (string $key) use ($attributes): bool {
                return ! in_array($key, $attributes);
            },
            \ARRAY_FILTER_USE_KEY
        );
    }
}

if (! function_exists('has_fillable_attributes')) {
    /**
     * Determine if the given $data array has the majority of the given fillable attributes.
     *
     * @param  array<string>  $data
     * @param  array<string>  $attributes
     */
    function has_fillable_attributes(array $data, array $attributes): bool
    {
        $filtered = filter_attributes($data, $attributes);

        return count($filtered) >= count($attributes);
    }
}
