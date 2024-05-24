<?php

namespace Ignite\Auth\Enums;

enum Auth: string
{
    case DEFAULT_GUARD = 'web';
    case EMAIL = 'email';
    case USERNAME = 'username';
    case PASSWORD = 'password';
    case REMEMBER = 'remember';
    case HOME = '/home';
}
