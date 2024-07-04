<?php

namespace Ignite\Auth\Actions;

use Ignite\Interfaces\Auth\Role;
use Ignite\Interfaces\Auth\UpdatesRolePermissions;

class UpdateRolePermissions implements UpdatesRolePermissions
{
    /**
     * Update the given role's permissions.
     *
     * @param \Ignite\Interfaces\Auth\Role $role
     * @param array                        $permissions
     *
     * @return void
     */
    public function update(Role $role, array $permissions): void
    {
        $role->allowTo($permissions, detach: true);
    }
}
