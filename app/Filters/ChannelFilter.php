<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;

class ChannelFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<int, string>
     */
    protected array $filters = ['name'];

    /**
     * Filter the query by a given name value.
     */
    protected function name(string $value): Builder
    {
        return $this->getBuilder()->where('name', $value);
    }
}
