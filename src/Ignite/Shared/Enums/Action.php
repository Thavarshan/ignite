<?php

namespace Ignite\Shared\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Action: string
{
    use FetchAll;

    case ALL = '*';
    case VIEW_ANY = 'viewAny';
    case VIEW = 'view';
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case LOCK = 'lock';
    case UNLOCK = 'unlock';
    case DOWNLOAD = 'download';
}
