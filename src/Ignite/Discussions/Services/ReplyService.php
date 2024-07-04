<?php

namespace Ignite\Discussions\Services;

use Ignite\Interfaces\Discussions\ReplyFilter;
use Ignite\Interfaces\Discussions\ReplyRepository;
use Ignite\Interfaces\Discussions\ReplyService as ReplyServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ReplyService extends Service implements ReplyServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Discussions\ReplyRepository $repository
     *
     * @return void
     */
    public function __construct(ReplyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Discussions\ReplyFilter $filter
     * @param bool                                       $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        ReplyFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        return $this->repository->list($filter);
    }
}
