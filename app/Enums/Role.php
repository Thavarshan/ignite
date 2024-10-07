<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case AGENT = 'agent';
    case USER = 'user';
    case GUEST = 'guest';
}
