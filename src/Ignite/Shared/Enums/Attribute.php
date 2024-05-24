<?php

namespace Ignite\Shared\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Attribute: string
{
    use FetchAll;

    case ID = 'id';
    case UID = 'uid';
    case NAME = 'name';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case SLUG = 'slug';
    case TITLE = 'title';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
    case DELETED_AT = 'deleted_at';
    case LOCKED_AT = 'locked_at';
}
