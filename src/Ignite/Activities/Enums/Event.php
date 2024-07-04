<?php

namespace Ignite\Activities\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;

enum Event: string
{
    use FetchAll;

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';

    /**
     * Get all of the default event cases.
     *
     * @return array
     */
    public static function defaults(): array
    {
        return [
            self::CREATED->value,
            self::UPDATED->value,
            self::DELETED->value,
        ];
    }
}
