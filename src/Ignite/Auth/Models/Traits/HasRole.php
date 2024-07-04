<?php

namespace Ignite\Auth\Models\Traits;

use Ignite\Auth\Enums\Permission as PermissionEnum;
use Ignite\Auth\Enums\Role as RoleEnum;
use Ignite\Auth\Exceptions\AuthorisationException;
use Ignite\Auth\Models\Role;
use Ignite\Interfaces\Auth\Permission;
use Ignite\Interfaces\Auth\Role as RoleInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

trait HasRole
{
    /**
     * Determine if user is assigned the given role.
     *
     * @param \Ignite\Interfaces\Auth\Role|array|string|null $role
     *
     * @return bool
     */
    public function hasRole(RoleInterface|array|string|null $role = null): bool
    {
        if (is_null($role)) {
            return false;
        }

        if (is_string($role)) {
            $role = $this->findRole($role);
        }

        if (is_array($role)) {
            return $this->hasAnyRole($role);
        }

        return $this->roles->contains($role);
    }

    /**
     * Determine if user is assigned any of the given roles.
     *
     * @param array<string> $roles
     *
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }

    /**
     * Assign role to model.
     *
     * @param \Ignite\Interfaces\Auth\Role|string $role
     *
     * @return void
     */
    public function assignRole(RoleInterface|RoleEnum|string $role): void
    {
        $previous = $this->getRole();

        if ($previous) {
            $this->unassignRole($previous);
        }

        $this->roles()->attach($this->getRoleEntity($role));
    }

    /**
     * Unassign role from model.
     *
     * @param \Ignite\Interfaces\Auth\Role|string $role
     *
     * @return void
     */
    public function unassignRole(RoleInterface|RoleEnum|string $role): void
    {
        $this->roles()->detach($this->getRoleEntity($role));
    }

    /**
     * Get role entity.
     *
     * @param \Ignite\Interfaces\Auth\Role|string $role
     *
     * @return \Ignite\Interfaces\Auth\Role
     *
     * @throws \Ignite\Auth\Exceptions\AuthorisationException
     */
    public function getRoleEntity(RoleInterface|RoleEnum|string $role): RoleInterface
    {
        $roleEntity = $role instanceof RoleInterface
            ? $role
            : $this->findRole(
                $role instanceof RoleEnum ? $role->value : $role
            );

        if (is_null($roleEntity)) {
            throw AuthorisationException::roleNotFound($role);
        }

        if ($roleEntity instanceof Collection) {
            $roleEntity = $roleEntity->first();
        }

        return $roleEntity;
    }

    /**
     * Find given role in database.
     *
     * @param \Ignite\Auth\Enums\Role|string|null $role
     *
     * @return \Ignite\Auth\Models\Role|\Illuminate\Database\Eloquent\Collection|null $role // phpcs:ignore
     */
    public function findRole(RoleEnum|string|null $role): Role|Collection|null
    {
        try {
            return is_null($role) ? $this->roles : Role::findByName(
                $role instanceof RoleEnum ? $role->value : $role
            );
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * Get the role of the model.
     *
     * @return \Ignite\Interfaces\Auth\Role|null
     */
    public function getRole(): ?RoleInterface
    {
        return $this->roles()->first();
    }

    /**
     * Get the role attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function role(): Attribute
    {
        return Attribute::make(fn () => $this->getRole());
    }

    /**
     * Get the role of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Fetch the user's permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function permissions(): BaseCollection
    {
        return $this->roles
            ->map
            ->permissions
            ->flatten()
            ->pluck('name')
            ->unique();
    }

    /**
     * Give a user permission to perform an action.
     *
     * @param \Ignite\Interfaces\Auth\Permission|array|string $permission
     *
     * @return void
     */
    public function allowTo(Permission|array|string $permission): void
    {
        $this->getRole()->allowTo($permission);
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermissionTo(string $permission): bool
    {
        [$resource, $action] = explode(':', $permission);

        $resourcePermissions = $this->getResourcePermissions($resource);

        if ($resourcePermissions->contains(PermissionEnum::ALL->value)) {
            return true;
        }

        if ($action === PermissionEnum::ALL->value) {
            return collect(PermissionEnum::crud())
                ->every(function ($permission) use ($resourcePermissions) {
                    return $resourcePermissions->contains($permission);
                });
        }

        return $this->permissions()->contains($permission);
    }

    /**
     * Get the user's permissions for a given resource.
     *
     * @param string $resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResourcePermissions(string $resource): BaseCollection
    {
        return $this->permissions()
            ->filter(fn ($value) => Str::startsWith($value, $resource))
            ->map(fn ($value) => Str::after($value, ':'));
    }

    /**
     * Determine if user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN->value);
    }

    /**
     * Determine if user is a staff member.
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->hasRole(RoleEnum::STAFF->value);
    }

    /**
     * Determine if user is a client owner.
     *
     * @return bool
     */
    public function isClientOwner(): bool
    {
        return $this->hasRole(RoleEnum::CLIENT_OWNER->value);
    }

    /**
     * Determine if user is a client manager.
     *
     * @return bool
     */
    public function isClientManager(): bool
    {
        return $this->hasRole(RoleEnum::CLIENT_MANAGER->value);
    }

    /**
     * Determine if user is a client staff.
     *
     * @return bool
     */
    public function isClientStaff(): bool
    {
        return $this->hasRole(RoleEnum::CLIENT_STAFF->value);
    }
}
