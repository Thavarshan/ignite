<?php

namespace App\Enums;

enum Notification: string
{
    case MAIL = 'mail';
    case SLACK = 'slack';
    case DATABASE = 'database';
}
