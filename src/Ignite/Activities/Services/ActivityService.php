<?php

namespace Ignite\Activities\Services;

use Ignite\Interfaces\Activities\ActivityFilter;
use Ignite\Interfaces\Activities\ActivityRepository;
use Ignite\Interfaces\Activities\ActivityService as ActivityServiceInterface;
use Ignite\Interfaces\Users\User;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ActivityService extends Service implements ActivityServiceInterface
{
    /**
     * The activity repository implementation.
     *
     * @param \Ignite\Interfaces\Activities\ActivityRepository $repository
     *
     * @return void
     */
    public function __construct(ActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch an activity feed for the given user.
     *
     * @param \Ignite\Interfaces\Activities\ActivityFilter $filter
     * @param \Ignite\Interfaces\Users\User|null           $user
     * @param bool                                         $pagniate
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function feed(
        ActivityFilter $filter,
        ?User $user = null,
        bool $pagniate = true
    ): Collection|Paginator {
        $query = $this->repository
            ->list($filter)
            ->when($user, function (Builder $query) use ($user) {
                return $query->where('user_id', $user->getId());
            })
            ->latest()
            ->with('subject');

        return $pagniate ? $query->paginate(30) : $query->get();
    }
}
