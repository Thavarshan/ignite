<?php

namespace App\Support;

trait Newable
{
    /**
     * Create a new instance of the class.
     *
     * @param mixed ...$arguments
     *
     * @return self
     */
    public static function new(mixed ...$arguments): self
    {
        return new self(...$arguments);
    }
}
