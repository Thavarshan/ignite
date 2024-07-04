<?php

namespace Ignite\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Auth\Http\Requests\UpdateRolePermissionsRequest;
use Ignite\Auth\Http\Responses\RoleResponse;
use Ignite\Interfaces\Auth\Role;
use Ignite\Interfaces\Auth\UpdatesRolePermissions;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Auth\Http\Requests\UpdateRolePermissionsRequest $request
     * @param \Ignite\Interfaces\Auth\Role                            $role
     * @param \Ignite\Interfaces\Auth\UpdatesRolePermissions          $updater
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(
        UpdateRolePermissionsRequest $request,
        Role $role,
        UpdatesRolePermissions $updater
    ): Response|Responsable {
        $updater->update($role, $request->input('permissions'));

        return RoleResponse::dispatch($role);
    }
}
