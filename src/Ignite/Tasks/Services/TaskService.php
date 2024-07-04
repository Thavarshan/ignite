<?php

namespace Ignite\Tasks\Services;

use Ignite\Interfaces\Tasks\TaskFilter;
use Ignite\Interfaces\Tasks\TaskRepository;
use Ignite\Interfaces\Tasks\TaskService as TaskServiceInterface;
use Ignite\Interfaces\Users\User;
use Ignite\Shared\Data\Services\Service;
use Ignite\Shared\Data\Services\Traits\FilterApplicator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class TaskService extends Service implements TaskServiceInterface
{
    use FilterApplicator;

    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Tasks\TaskRepository $repository
     *
     * @return void
     */
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Tasks\TaskFilter $filter
     * @param bool                                $paginated
     * @param \Ignite\Interfaces\Users\User|null  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        TaskFilter $filter,
        bool $paginated = false,
        ?User $user = null
    ): Builder|Paginator {
        // Task listing should be filtered by organisation if the user is not an admin.
        $query = $this->repository
            ->list($this->applyScopedFilter($filter, $user))
            ->latest();

        if ($paginated) {
            return $query->paginate();
        }

        return $query;
    }
}
