<?php

namespace Ignite\Auth\Services;

use Ignite\Interfaces\Auth\PermissionFilter;
use Ignite\Interfaces\Auth\PermissionRepository;
use Ignite\Interfaces\Auth\PermissionService as PermissionServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class PermissionService extends Service implements PermissionServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Auth\PermissionRepository $repository
     *
     * @return void
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Auth\PermissionFilter $filter
     * @param bool                                     $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        PermissionFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        return $this->repository->list($filter);
    }
}
