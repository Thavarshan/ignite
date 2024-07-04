<?php

namespace Ignite\Auth\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum AuthEvent: string
{
    use FetchAll;

    case LOCKED = 'locked';
    case UNLOCKED = 'unlocked';
    case LOGGED_IN = 'loggedIn';
    case LOGGED_OUT = 'loggedOut';
}
