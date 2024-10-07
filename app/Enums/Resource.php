<?php

namespace App\Enums;

enum Resource: string
{
    case USER = 'user';
    case ROLE = 'role';
    case PERMISSION = 'permission';
    case CHANNEL = 'channel';
    case THREAD = 'thread';
    case NOTE = 'note';
    case REPLY = 'reply';
    case ACTIVITY = 'activity';
}
