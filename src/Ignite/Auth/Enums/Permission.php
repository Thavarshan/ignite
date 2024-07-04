<?php

namespace Ignite\Auth\Enums;

use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Shared\Enums\Traits\FetchAll;
use Illuminate\Support\Arr;

enum Permission: string
{
    use FetchAll;

    case ALL = '*';
    case CREATE = Action::CREATE->value;
    case READ = Action::READ->value;
    case UPDATE = Action::UPDATE->value;
    case DELETE = Action::DELETE->value;

    /**
     * Get the CRUD permissions.
     *
     * @return array
     */
    public static function crud(): array
    {
        return array_map(
            fn (Action $action) => $action->value,
            [self::CREATE, self::READ, self::UPDATE, self::DELETE]
        );
    }

    /**
     * Get the permissions for the given resource.
     *
     * @param \Ignite\Shared\Enums\Resource $resource
     * @param bool                          $withResource
     *
     * @return array
     */
    public static function forResources(
        Resource $resource,
        ?bool $withResource = false
    ): array {
        $permissions = Arr::get(
            config('permissions'),
            $resource->value,
            config('permissions.default')
        );

        if ($withResource) {
            $permissions = array_map(
                fn (string $permission) => "{$resource->value}:{$permission}",
                $permissions
            );
        }

        return $permissions;
    }

    /**
     * Get the permission for the given action on the given resource.
     *
     * @param \Ignite\Shared\Enums\Action   $action
     * @param \Ignite\Shared\Enums\Resource $resource
     *
     * @return string
     */
    public static function to(
        Action|string $action,
        Resource|string $resource
    ): string {
        if ($action instanceof Action) {
            $action = $action->value;
        }

        if ($resource instanceof Resource) {
            $resource = $resource->value;
        }

        return "{$resource}:{$action}";
    }
}
