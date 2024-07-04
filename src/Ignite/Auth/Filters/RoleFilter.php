<?php

namespace Ignite\Auth\Filters;

use Ignite\Interfaces\Auth\RoleFilter as RoleFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class RoleFilter extends Filter implements RoleFilterInterface
{
    /**
     * Attributes to filters from.
     *
     * @var array<string>
     */
    protected $filters = ['slug'];

    /**
     * Filter roles by given slug.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function slug(string $slug): Builder
    {
        return $this->builder->where('slug', $slug);
    }
}
