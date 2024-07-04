<?php

namespace App\Exceptions;

use DomainException;

abstract class ResourceException extends DomainException
{
    /**
     * Throw new invalid resource exception.
     *
     * @param string|null $message
     *
     * @return \App\Exceptions\ResourceException
     */
    public static function invalid(?string $message = null): ResourceException
    {
        return new static($message ?? 'Resource invalid.'); // @phpstan-ignore-line
    }
}
