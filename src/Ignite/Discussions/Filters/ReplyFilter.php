<?php

namespace Ignite\Discussions\Filters;

use Ignite\Interfaces\Discussions\ReplyFilter as ReplyFilterInterface;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ReplyFilter extends Filter implements ReplyFilterInterface
{
    /**
     * Attributes to filters from.
     *
     * @var array<string>
     */
    protected $filters = [
        'user',
        'created_at',
    ];

    /**
     * Filter replies by user.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user(int $id): Builder
    {
        return $this->builder->where('user_id', $id);
    }

    /**
     * Filter the query by a given created at date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createdAt(string $date): Builder
    {
        return $this->builder->whereDate('created_at', $date);
    }
}
