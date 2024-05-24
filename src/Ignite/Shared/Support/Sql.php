<?php

namespace Ignite\Shared\Support;

class Sql
{
    /**
     * Pluck columns of a table.
     *
     * @param string $table
     * @param array  $columns
     *
     * @return string
     */
    public static function pluckColumnsOf(string $table, array $columns): string
    {
        return implode(',', array_map(fn ($column) => "{$table}.{$column}", $columns));
    }
}
