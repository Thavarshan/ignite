<?php

namespace Ignite\Activities\Filters;

use Ignite\Interfaces\Activities\ActivityFilter as ActivityFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ActivityFilter extends Filter implements ActivityFilterInterface
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<string>
     */
    protected $filters = ['type'];

    /**
     * Filter the query by a given type.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type(string $type): Builder
    {
        return $this->builder->where('type', $type);
    }
}
