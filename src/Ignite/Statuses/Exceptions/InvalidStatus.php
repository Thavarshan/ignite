<?php

namespace Ignite\Statuses\Exceptions;

use Exception;

class InvalidStatus extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $name
     *
     * @return static
     */
    public static function create(string $name): self
    {
        return new self("The status `{$name}` is an invalid status.");
    }
}
