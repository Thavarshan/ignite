<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ThreadFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array<int, string>
     */
    protected array $filters = [
        'title',
        'slug',
        'locked',
        'unlocked',
        'channel',
        'author',
    ];

    /**
     * Filter the query by a given title value.
     */
    protected function title(string $value): Builder
    {
        return $this->getBuilder()->where('title', $value);
    }

    /**
     * Filter the query by a given slug value.
     */
    protected function slug(string $value): Builder
    {
        return $this->getBuilder()->where('slug', $value);
    }

    /**
     * Filter the query by a given locked value.
     */
    protected function locked(): QueryBuilder
    {
        return $this->getBuilder()->whereNotNull('locked_at');
    }

    /**
     * Filter the query by a given unlocked value.
     */
    protected function unlocked(): QueryBuilder
    {
        return $this->getBuilder()->whereNull('locked_at');
    }

    /**
     * Filter the query by a given channel value.
     */
    protected function channel(string $value): Builder
    {
        return $this->getBuilder()->whereHas('channel', function (Builder $query) use ($value): void {
            $query->where('slug', $value);
        });
    }

    /**
     * Filter the query by a given author value.
     */
    protected function author(string $value): Builder
    {
        return $this->getBuilder()->whereHas('author', function (Builder $query) use ($value): void {
            $query->where('name', $value);
        });
    }
}
