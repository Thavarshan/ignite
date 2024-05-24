<?php

namespace Ignite\Shared\Rules;

abstract class RegexRule
{
    /**
     * The value pattern to compare against.
     *
     * @var string
     */
    protected static $pattern;

    /**
     * Set a custom validation pattern to match values against.
     *
     * @param string $pattern
     *
     * @return void
     */
    public static function setPattern(string $pattern): void
    {
        static::$pattern = $pattern;
    }

    /**
     * Get the validation pattern to match values against.
     *
     * @return string
     */
    public static function getPattern(): string
    {
        return static::$pattern;
    }

    /**
     * Validate the value against the pattern.
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected static function validatePattern(mixed $value): bool
    {
        return preg_match(static::getPattern(), $value) === 1;
    }
}
