<?php

namespace Ignite\Auth\Exceptions;

use App\Exceptions\Traits\HasRenderables;
use App\Interfaces\Exceptions\RenderableException;
use Ignite\Auth\Http\Responses\AccountLockedResponse;
use Ignite\Auth\Http\Responses\IpNotAuthorisedResponse;
use Ignite\Interfaces\Users\User;
use Illuminate\Auth\AuthenticationException as IlluminateAuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationException extends IlluminateAuthenticationException implements RenderableException
{
    use HasRenderables;

    /**
     * Create a new authentication exception for invalid credentials.
     *
     * @param string|null $message
     *
     * @return self
     */
    public static function invalidCredentials(?string $message = null): self
    {
        $instance = new self($message ?? 'Invalid credentials.');

        self::setRederable(fn () => IpNotAuthorisedResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_UNAUTHORIZED
        ));

        return $instance;
    }

    /**
     * Create a new authentication exception for invalid credentials.
     *
     * @param string|null $ip
     *
     * @return self
     */
    public static function ipNotAuthorised(?string $ip = null): self
    {
        $instance = new self($ip ? "IP address [{$ip}] is not authorised." : 'IP address is not authorised.'); // phpcs:ignore

        self::setRederable(fn () => IpNotAuthorisedResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_UNAUTHORIZED
        ));

        return $instance;
    }

    /**
     * Create a new authentication exception for invalid IP address.
     *
     * @param string|null $ip
     *
     * @return self
     */
    public static function invalidIp(?string $ip = null): self
    {
        $instance = new self($ip ? "Invalid IP address [{$ip}]." : 'Invalid IP address.');

        self::setRederable(fn () => IpNotAuthorisedResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_UNAUTHORIZED
        ));

        return $instance;
    }

    /**
     * Create a new authentication exception for invalid IP address range.
     *
     * @param string|null $message
     *
     * @return self
     */
    public static function invalidIpRange(?string $message = null): self
    {
        $instance = new self($message ?? 'Invalid IP address range.');

        self::setRederable(fn () => IpNotAuthorisedResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_UNAUTHORIZED
        ));

        return $instance;
    }

    /**
     * Create a new authentication exception for user account is locked.
     *
     * @param \Ignite\Interfaces\Users\User|null $user
     *
     * @return self
     */
    public static function accountLocked(?User $user = null): self
    {
        $instance = new self(
            $user
                ? "User account [{$user->getEmail()}] is locked."
                : 'User account is locked.'
        );

        self::setRederable(fn () => AccountLockedResponse::dispatch(
            ['message' => $instance->getMessage()],
            Response::HTTP_FORBIDDEN
        ));

        return $instance;
    }
}
