<?php

namespace Ignite\Auth\Filters;

use Ignite\Interfaces\Auth\PermissionFilter as PermissionFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PermissionFilter extends Filter implements PermissionFilterInterface
{
    /**
     * Attributes to filters from.
     *
     * @var array<string>
     */
    protected $filters = ['slug'];

    /**
     * Filter permissions by given slug.
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
