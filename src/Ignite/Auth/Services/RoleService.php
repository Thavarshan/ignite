<?php

namespace Ignite\Auth\Services;

use Ignite\Interfaces\Auth\RoleFilter;
use Ignite\Interfaces\Auth\RoleRepository;
use Ignite\Interfaces\Auth\RoleService as RoleServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class RoleService extends Service implements RoleServiceInterface
{
    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Auth\RoleRepository $repository
     *
     * @return void
     */
    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Auth\RoleFilter $filter
     * @param bool                               $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        RoleFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        return $this->repository
            ->list($filter)
            ->with('permissions')
            ->latest();
    }
}
