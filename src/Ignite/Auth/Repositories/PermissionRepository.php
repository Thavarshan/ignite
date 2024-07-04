<?php

namespace Ignite\Auth\Repositories;

use Ignite\Interfaces\Auth\Permission;
use Ignite\Interfaces\Auth\PermissionRepository as PermissionRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class PermissionRepository extends Repository implements PermissionRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Auth\Permission $permission
     *
     * @return void
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }
}
