<?php

namespace App\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum AppMode: string
{
    use FetchAll;

    case WEB = 'web';
    case API = 'api';
    case CLI = 'cli';
}
