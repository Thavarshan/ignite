<?php

namespace Ignite\Auth\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Role: string
{
    use FetchAll;

    case ADMIN = 'Admin';
    case STAFF = 'Staff';
    case CLIENT_OWNER = 'Client_Owner';
    case CLIENT_MANAGER = 'Client_Manager';
    case CLIENT_STAFF = 'Client_Staff';

    /**
     * Get the default role.
     *
     * @return string
     */
    public static function default(): string
    {
        return self::CLIENT_STAFF->value;
    }

    /**
     * Get only organisation specific roles.
     *
     * @return array<int, string>
     */
    public static function organisationSpecific(): array
    {
        return [
            self::CLIENT_OWNER->value,
            self::CLIENT_MANAGER->value,
            self::CLIENT_STAFF->value,
        ];
    }
}
