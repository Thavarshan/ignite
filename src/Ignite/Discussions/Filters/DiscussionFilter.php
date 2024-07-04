<?php

namespace Ignite\Discussions\Filters;

use Ignite\Interfaces\Discussions\DiscussionFilter as DiscussionFilterInterface;
use Ignite\Interfaces\Organisations\OrganisationService;
use Ignite\Interfaces\Organisations\TeamService;
use Ignite\Shared\Data\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DiscussionFilter extends Filter implements DiscussionFilterInterface
{
    /**
     * Attributes to filters from.
     *
     * @var array<string>
     */
    protected $filters = [
        'category',
        'team',
        'organisation',
        'created_at',
    ];

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ignite\Interfaces\Organisations\OrganisationService $organisations
     * @param \Ignite\Interfaces\Organisations\TeamService         $teams
     *
     * @return void
     */
    public function __construct(
        Request $request,
        protected readonly OrganisationService $organisations,
        protected readonly TeamService $teams
    ) {
        parent::__construct($request);
    }

    /**
     * Filter discussion by given organisation.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function organisation(string $slug): Builder
    {
        return $this->builder->whereBelongsTo(
            $this->organisations->findBy('slug', $slug)
        );
    }

    /**
     * Filter discussion by given team.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function team(string $slug): Builder
    {
        return $this->builder->whereBelongsTo(
            $this->teams->findBy('slug', $slug)
        );
    }

    /**
     * Filter discussion by given category.
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function category(string $slug): Builder
    {
        return $this->builder->whereHas(
            'categories',
            function (Builder $query) use ($slug): void {
                $query->where('slug', $slug);
            }
        );
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
