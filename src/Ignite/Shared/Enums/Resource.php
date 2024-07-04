<?php

namespace Ignite\Shared\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;
use InvalidArgumentException;

enum Resource: string
{
    use FetchAll;

    case PERMISSIONS = 'permissions';
    case ROLES = 'roles';
    case SETTINGS = 'settings';
    case TEAMS = 'teams';
    case INVITATIONS = 'invitations';
    case USERS = 'users';
    case DISCUSSIONS = 'discussions';
    case REPLIES = 'replies';
    case CHANNELS = 'channels';
    case NOTES = 'notes';
    case ACCOUNTS = 'accounts';
    case PRICES = 'prices';
    case PAYMENTS = 'payments';
    case PAYMENT_METHODS = 'payment_methods';
    case DISPATCH_METHODS = 'dispatch_methods';
    case CHARGES = 'charges';
    case INVOICES = 'invoices';
    case TASKS = 'tasks';

    /**
     * Check if the given resource is valid.
     *
     * @param string $resource
     *
     * @return bool
     */
    public static function isValid(string $resource): bool
    {
        return in_array($resource, self::all());
    }

    /**
     * Get the proper resource value from the given table name string value.
     *
     * @param string $table
     *
     * @return \Ignite\Shared\Enums\Resource
     */
    public static function fromTable(string $table): Resource
    {
        return match ($table) {
            'permissions' => self::PERMISSIONS,
            'roles' => self::ROLES,
            'settings' => self::SETTINGS,
            'teams' => self::TEAMS,
            'invitations' => self::INVITATIONS,
            'users' => self::USERS,
            'tasks' => self::TASKS,
            'discussions' => self::DISCUSSIONS,
            'replies' => self::REPLIES,
            'categories' => self::CHANNELS,
            default => throw new InvalidArgumentException("Invalid table name: {$table}"),
        };
    }
}
