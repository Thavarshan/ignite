<?php

namespace Ignite\Shared\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Resource: string
{
    use FetchAll;

    case PERMISSIONS = 'permissions';
    case ROLES = 'roles';
    case SETTINGS = 'settings';
    case ORGANISATIONS = 'organisations';
    case TEAMS = 'teams';
    case INVITATIONS = 'invitations';
    case USERS = 'users';
    case LOCATIONS = 'locations';
    case TASKS = 'tasks';
    case POLICE = 'police';
    case REPORTS = 'reports';
    case QUESTIONS = 'questions';
    case MATERIALS = 'materials';
    case INSTRUCTIONS = 'instructions';
    case DISCUSSIONS = 'discussions';
    case REPLIES = 'replies';
    case CATEGORIES = 'categories';

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
}
