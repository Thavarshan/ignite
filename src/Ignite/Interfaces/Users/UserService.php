<?php

namespace Ignite\Interfaces\Users;

use Ignite\Interfaces\Shared\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

interface UserService extends Service
{
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
    ): Builder|Paginator;
}
