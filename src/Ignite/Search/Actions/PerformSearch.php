<?php

namespace Ignite\Search\Actions;

use DomainException;
use Ignite\Interfaces\Search\EngineManager;
use Ignite\Interfaces\Search\PerformsSearches;
use Ignite\Search\Support\Query as SearchQuery;
use Ignite\Search\Traits\ScopesToUser;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Laravel\Pennant\Feature;

class PerformSearch implements PerformsSearches
{
    use ScopesToUser;

    /**
     * PerformSearch constructor.
     *
     * @param \Ignite\Interfaces\Search\EngineManager $engine
     *
     * @return void
     */
    public function __construct(protected EngineManager $engine)
    {
    }

    /**
     * Perform a search.
     *
     * @param string $query
     * @param bool   $paginate
     *
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function search(
        string $query,
        bool $paginate = false
    ): Collection|Paginator {
        if (! Feature::active('search')) {
            throw new DomainException('Search feature is not enabled.');
        }

        return $this->engine
            ->scopeToUser($this->user)
            ->search(new SearchQuery($query), $paginate);
    }
}
