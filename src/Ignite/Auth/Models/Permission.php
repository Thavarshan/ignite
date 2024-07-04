<?php

namespace Ignite\Auth\Models;

use Ignite\Interfaces\Auth\Permission as PermissionInterface;
use Ignite\Shared\Data\Traits\Entity;
use Ignite\Shared\Data\Traits\Filterable;
use Ignite\Shared\Traits\HasName;
use Ignite\Shared\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string                          $name
 * @property string                          $slug
 * @property array<\Ignite\Auth\Models\Role> $roles
 */
class Permission extends Model implements PermissionInterface
{
    use HasFactory;
    use Entity;
    use HasName;
    use Sluggable;
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
     * An ability may have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Filter the given permissions to only the ones that exist.
     *
     * @param array<string> $permissions
     *
     * @return array<string>
     */
    public function valid(array $permissions): array
    {
        return collect($permissions)
            ->map(fn (string $permission) => $this->whereName($permission)->first())
            ->filter()
            ->map(fn (Permission $permission) => $permission->name)
            ->values()
            ->toArray();
    }
}
