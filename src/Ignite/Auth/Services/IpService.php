<?php

namespace Ignite\Auth\Services;

use Ignite\Interfaces\Auth\IpFilter;
use Ignite\Interfaces\Auth\IpRepository;
use Ignite\Interfaces\Auth\IpService as IpServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class IpService extends Service implements IpServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Auth\IpRepository $repository
     *
     * @return void
     */
    public function __construct(IpRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Auth\IpFilter $filter
     * @param bool                             $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        IpFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        $query = $this->repository
            ->list($filter)
            ->latest();

        if ($paginated) {
            return $query->paginate();
        }

        return $query;
    }

    /**
     * List all unauthorised ip addresses.
     *
     * @param \Ignite\Interfaces\Auth\IpFilter $filter
     * @param bool                             $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function unauthorised(
        IpFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        $query = $this->repository
            ->list($filter->appendFilterable('authorised', false))
            ->latest();

        if ($paginated) {
            return $query->paginate();
        }

        return $query;
    }
}
