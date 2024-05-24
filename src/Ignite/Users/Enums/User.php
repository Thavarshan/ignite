<?php

namespace Ignite\Users\Enums;

use Ignite\Shared\Enums\Attribute;
use Ignite\Shared\Enums\Traits\FetchAll;

enum User: string
{
    use FetchAll;

    case UID = Attribute::UID->value;
    case NAME = Attribute::NAME->value;
    case EMAIL = Attribute::EMAIL->value;
    case PHONE = Attribute::PHONE->value;
    case PASSWORD = 'password';
}
