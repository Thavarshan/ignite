<?php

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

if (! function_exists('route_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param string $path
     *
     * @return string
     */
    function route_path(string $path = ''): string
    {
        $path = $path ? \DIRECTORY_SEPARATOR . ltrim($path, \DIRECTORY_SEPARATOR) : ''; // phpcs:ignore

        return app()->basePath("routes{$path}");
    }
}

if (! function_exists('of')) {
    /**
     * Create an object with the given properties.
     *
     * @param mixed ...$arguments
     *
     * @return object
     */
    function of(...$arguments): object
    {
        return (object) $arguments;
    }
}

if (! function_exists('cast_to_json')) {
    /**
     * Generate a raw DB query to search for a JSON field.
     *
     * @param array $json
     *
     * @return \Illuminate\Contracts\Database\Query\Expression
     *
     * @throws \Exception
     */
    function cast_to_json(array $json): Expression
    {
        if (is_array($json)) {
            $json = addslashes(json_encode($json));
        }

        return DB::raw("CAST('{$json}' AS JSON)");
    }
}

if (! function_exists('ping')) {
    /**
     * Dump the given value and end the script.
     *
     * @param string|null $message
     *
     * @return void
     */
    function ping(?string $message = null): void
    {
        dump($message ?? 'Ping');
    }
}

if (! function_exists('clean_null_bytes')) {
    /**
     * Cleans a string of null byte characters.
     *
     * @param string $value The input string to be sanitized.
     *
     * @return string
     */
    function clean_null_bytes(string $value): string
    {
        return str_replace("\0", '', $value);
    }
}

if (! function_exists('extract_quoted_text')) {
    /**
     * Extracts text within double quotes from a string.
     *
     * @param string $input The input string to extract text from.
     *
     * @return array
     */
    function extract_quoted_text(string $input): array
    {
        // Pattern to find text within double quotes
        $pattern = '/"([^"]*)"/';
        $matches = [];

        // Perform the regular expression match
        if (preg_match_all($pattern, $input, $matches)) {
            // Check if there are matches and return them
            if (! empty($matches[1])) {
                return $matches[1];
            }
        }

        // Return an empty array if no matches were found
        return [];
    }
}

if (! function_exists('custom_rule')) {
    /**
     * Get a serialisable custom rule object that has been bound to the IoC container.
     *
     * @param string $name
     *
     * @return \Illuminate\Contracts\Validation\ValidationRule
     */
    function custom_rule(string $name): ValidationRule
    {
        return app($name);
    }
}
