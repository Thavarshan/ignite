<?php

namespace App\Enums;

enum ControllerMethod: string
{
    case INDEX = 'index';
    case CREATE = 'create';
    case STORE = 'store';
    case SHOW = 'show';
    case EDIT = 'edit';
    case UPDATE = 'update';
    case DESTROY = 'destroy';

    /**
     * Returns all cases.
     *
     * @return array<string>
     */
    public static function all(): array
    {
        return array_map(
            fn (self $case) => $case->value,
            self::cases()
        );
    }

    /**
     * Only returns the cases requested.
     *
     * @return array<string>
     */
    public static function only(array $cases): array
    {
        return array_filter(
            self::all(),
            fn (string $case) => in_array($case, $cases, true)
        );
    }

    /**
     * Returns all cases relating to CRUD operations.
     *
     * @return array<string>
     */
    public static function crudOnly(): array
    {
        return self::only([
            self::INDEX->value,
            self::STORE->value,
            self::SHOW->value,
            self::UPDATE->value,
            self::DESTROY->value,
        ]);
    }

    /**
     * Returns all cases relating to views.
     *
     * @return array<string>
     */
    public static function viewsOnly(): array
    {
        return self::only([
            self::INDEX->value,
            self::SHOW->value,
            self::CREATE->value,
            self::EDIT->value,
        ]);
    }
}
