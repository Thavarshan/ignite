<?php

namespace Ignite\Shared\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Priority: string
{
    use FetchAll;

    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
