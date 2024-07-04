<?php

namespace Ignite\Settings\Filters;

use Ignite\Interfaces\Settings\SettingsFilter as SettingsFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SettingsFilter extends Filter implements SettingsFilterInterface
{
    /**
     * Filter settings by given key.
     *
     * @param string $key
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function key(string $key): Builder
    {
        return $this->builder->whereRaw(
            'JSON_CONTAINS(`value`, \'{"key": "' . $key . '"}\')'
        );
    }
}
