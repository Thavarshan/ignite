<?php

namespace Ignite\Shared\Enums\Traits;

use Illuminate\Support\Collection;

trait FetchAll
{
    /**
     * Get all the values of the enum as an array.
     *
     * @param bool|null $asCollection
     *
     * @return \Illuminate\Support\Collection|array<string>
     */
    public static function all(?bool $asCollection = false): Collection|array
    {
        $results = array_map(
            fn (self $enumCase): string => $enumCase->value,
            self::cases()
        );

        return $asCollection ? collect($results) : $results;
    }
}
