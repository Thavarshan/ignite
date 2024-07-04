<?php

namespace Ignite\Auth\Exceptions;

use App\Exceptions\Traits\HasRenderables;
use App\Http\Responses\SimpleResponse;
use App\Interfaces\Exceptions\RenderableException;
use DomainException;
use Symfony\Component\HttpFoundation\Response;

class AuthorisationException extends DomainException implements RenderableException
{
    use HasRenderables;

    /**
     * Create a new exception instance for error userAlreadyHasRole.
     *
     * @return self
     */
    public static function userAlreadyHasRole(): self
    {
        $instance = new self('User already has a role which may be the same as the given.');

        self::setRederable(fn () => SimpleResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));

        return $instance;
    }

    /**
     * Create a new exception instance for error roleNotFound.
     *
     * @return self
     */
    public static function roleNotFound(string $role): self
    {
        $instance = new self("Role [{$role}] not found.");

        self::setRederable(fn () => SimpleResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_NOT_FOUND
        ));

        return $instance;
    }
}
