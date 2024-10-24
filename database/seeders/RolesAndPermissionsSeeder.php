<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(config('authorisation'))->each(function ($resources, $role) {
            $role = Role::findOrCreate($role);
            collect($resources)->each(function ($permissions, $resource) use ($role) {
                collect($permissions)->each(function ($permission) use ($role, $resource) {
                    $permission = Permission::findOrCreate($permission.':'.$resource);
                    $role->givePermissionTo($permission);
                });
            });
        });
    }
}
