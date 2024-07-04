<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\JsonEncodingException as IlluminateJsonEncodingException;

// phpcs:ignore

class JsonEncodingException extends IlluminateJsonEncodingException
{
    /**
     * Create a new JSON encoding exception for the resource.
     *
     * @param mixed  $entity
     * @param string $message
     *
     * @return self
     */
    public static function forEntity(mixed $entity, string $message): self
    {
        return new self('Error encoding entity [' . $entity::class . '] to JSON: ' . $message);
    }
}
