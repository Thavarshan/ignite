<?php

namespace Ignite\Shared\Data\Traits;

use Ignite\Interfaces\Shared\Filter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant space filters.
     *
     * @param \Ignite\Interfaces\Shared\Filter $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function filter(Filter $filters): Builder
    {
        return $filters->apply($this->newModelQuery());
    }
}
