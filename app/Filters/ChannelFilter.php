<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;

class ChannelFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<string>
     */
    protected array $filters = ['name'];

    /**
     * Filter the query by a name.
     */
    protected function name(string $value): Builder
    {
        return $this->builder->where('name', $value);
    }
}
