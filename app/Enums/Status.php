<?php

namespace App\Enums;

enum Status: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case PENDING = 'pending';
    case FLAGGED = 'flagged';
    case RESOLVED = 'resolved';
    case SPAM = 'spam';
    case TRASH = 'trash';
}
