<?php

namespace Ignite\Statuses\Exceptions;

use Exception;

class InvalidStatusModel extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $model
     *
     * @return static
     */
    public static function create(string $model): self
    {
        return new self("The model `{$model}` is invalid. A valid model must extend the model \Ignite\Statuses\Status."); // phpcs:ignore
    }
}
