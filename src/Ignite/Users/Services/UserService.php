<?php

namespace Ignite\Users\Services;

use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserFilter;
use Ignite\Interfaces\Users\UserRepository;
use Ignite\Interfaces\Users\UserService as UserServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Ignite\Shared\Data\Services\Traits\FilterApplicator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class UserService extends Service implements UserServiceInterface
{
    use FilterApplicator;

    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Users\UserRepository $repository
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Users\UserFilter $filter
     * @param bool                                $paginated
     * @param \Ignite\Interfaces\Users\User|null  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        UserFilter $filter,
        bool $paginated = false,
        ?User $user = null
    ): Builder|Paginator {
        // User listing should be filtered by organisation if the user is not an admin.
        $query = $this->repository
            ->list($this->applyScopedFilter($filter, $user))
            ->latest();

        if ($paginated) {
            return $query->paginate();
        }

        return $query;
    }
}
