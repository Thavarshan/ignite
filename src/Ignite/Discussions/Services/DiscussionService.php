<?php

namespace Ignite\Discussions\Services;

use Ignite\Interfaces\Discussions\DiscussionFilter;
use Ignite\Interfaces\Discussions\DiscussionRepository;
use Ignite\Interfaces\Discussions\DiscussionService as DiscussionServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class DiscussionService extends Service implements DiscussionServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Discussions\DiscussionRepository $repository
     *
     * @return void
     */
    public function __construct(DiscussionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Discussions\DiscussionFilter $filter
     * @param bool                                            $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        DiscussionFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        return $this->repository->list($filter);
    }
}
