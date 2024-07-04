<?php

namespace Ignite\Auth\Models;

use Ignite\Activities\Models\Traits\Trackable;
use Ignite\Auth\Enums\Role as RoleType;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\Permission as PermissionInterface;
use Ignite\Interfaces\Auth\Role as RoleInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Traits\HasName;
use Ignite\Shared\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                                      $id
 * @property string                                   $name
 * @property string                                   $slug
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 */
class Role extends Model implements RoleInterface
{
    use HasFactory;
    use Entity;
    use HasName;
    use Sluggable;
    use Trackable;
    use Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'permissions',
    ];

    /**
     * Grant the given permission to the role.
     *
     * @param \Ignite\Interfaces\Auth\Permission|array|string $permission
     * @param bool                                            $detach
     *
     * @return void
     */
    public function allowTo(
        PermissionInterface|array|string $permission,
        bool $detach = false
    ): void {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->get();
        } elseif (is_array($permission)) {
            $permission = Permission::whereIn('name', $permission)->get();
        }

        $this->permissions()->sync($permission, $detach);
    }

    /**
     * A role may have many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Determine if the role has any permissions.
     *
     * @return bool
     */
    public function hasPermissions(): bool
    {
        return $this->permissions->isNotEmpty();
    }

    /**
     * Get all users with this role assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Auth::userModel());
    }

    /**
     * Find role by name.
     *
     * @param string $role
     *
     * @return \Illuminate\Database\Eloquent\Model|\Ignite\Interfaces\Auth\Role
     */
    public static function findByName(string $role): Model|RoleInterface
    {
        return (new self())->findBy('name', $role);
    }

    /**
     * Get the admin role.
     *
     * @return \Ignite\Interfaces\Auth\Role
     */
    public static function admin(): RoleInterface
    {
        return self::findByName(RoleType::ADMIN->value);
    }

    /**
     * Get the staff role.
     *
     * @return \Ignite\Interfaces\Auth\Role
     */
    public static function staff(): RoleInterface
    {
        return self::findByName(RoleType::STAFF->value);
    }

    /**
     * Get the client owner role.
     *
     * @return \Ignite\Interfaces\Auth\Role
     */
    public static function clientOwner(): RoleInterface
    {
        return self::findByName(RoleType::CLIENT_OWNER->value);
    }

    /**
     * Get the client manager role.
     *
     * @return \Ignite\Interfaces\Auth\Role
     */
    public static function clientManager(): RoleInterface
    {
        return self::findByName(RoleType::CLIENT_MANAGER->value);
    }

    /**
     * Get the client staff role.
     *
     * @return \Ignite\Interfaces\Auth\Role
     */
    public static function clientStaff(): RoleInterface
    {
        return self::findByName(RoleType::CLIENT_STAFF->value);
    }

    /**
     * Determine if the role has the given permission.
     *
     * If an array is given and the comparison is made, the result should show that there are no differences.
     *
     * @param array<string>|string $permission
     *
     * @return bool
     */
    public function isAllowedTo(string|array $permission): bool
    {
        $permissions = $this->permissions->pluck('name');

        if ($permissions->isEmpty()) {
            return false;
        }

        if (is_array($permission)) {
            return collect($permission)
                ->diff($permissions)
                ->isEmpty();
        }

        return $permissions->contains($permission);
    }

    /**
     * Determine if the role is the given role.
     *
     * @param \Ignite\Auth\Enums\Role|string $roleName
     *
     * @return bool
     */
    public function assertIs(RoleType|string $roleName): bool
    {
        $role = $roleName instanceof RoleType ? $roleName->value : $roleName;

        return $this->getName() === $role;
    }
}
