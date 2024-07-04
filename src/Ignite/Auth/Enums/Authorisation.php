<?php

namespace Ignite\Auth\Enums;

use Illuminate\Support\Arr;

enum Authorisation: string
{
    /**
     * Get the permissions for the given role and resource.
     *
     * @param string $role
     *
     * @return array
     */
    public static function forRole(string $role): array
    {
        return Arr::get(
            config('authorisation'),
            $role,
            config('authorisation.default')
        );
    }
}
