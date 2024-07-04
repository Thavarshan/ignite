<?php

namespace Ignite\Auth\Filters;

use Ignite\Interfaces\Auth\IpFilter as IpFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class IpFilter extends Filter implements IpFilterInterface
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<string>
     */
    protected $filters = [
        'authorised',
    ];

    /**
     * Filter by authorised status.
     *
     * @param bool $authorised
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function authorised(bool $authorised): Builder
    {
        return with($this->builder, function (Builder $query) use ($authorised) {
            return $authorised
                ? $query->whereNotNull('authorised_at')
                : $query->whereNull('authorised_at');
        });
    }
}
