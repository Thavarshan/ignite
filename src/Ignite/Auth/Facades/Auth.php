<?php

namespace Ignite\Auth\Facades;

use BadMethodCallException;
use Ignite\Auth\Exceptions\AuthenticationException;
use Ignite\Interfaces\Auth\Credentials;
use Ignite\Interfaces\Users\User;
use Illuminate\Support\Facades\Auth as Facade;
use Illuminate\Support\Facades\Config;

// phpcs:disable
/**
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string|null $name = null)
 * @method static \Illuminate\Auth\SessionGuard                                             createSessionDriver(string $name, array $config)
 * @method static \Illuminate\Auth\TokenGuard                                               createTokenDriver(string $name, array $config)
 * @method static string                                                                    getDefaultDriver()
 * @method static void                                                                      shouldUse(string $name)
 * @method static void                                                                      setDefaultDriver(string $name)
 * @method static \Illuminate\Auth\AuthManager                                              viaRequest(string $driver, callable $callback)
 * @method static \Closure                                                                  userResolver()
 * @method static \Illuminate\Auth\AuthManager                                              resolveUsersUsing(\Closure $userResolver)
 * @method static \Illuminate\Auth\AuthManager                                              extend(string $driver, \Closure $callback)
 * @method static \Illuminate\Auth\AuthManager                                              provider(string $name, \Closure $callback)
 * @method static bool                                                                      hasResolvedGuards()
 * @method static \Illuminate\Auth\AuthManager                                              forgetGuards()
 * @method static \Illuminate\Auth\AuthManager                                              setApplication(\Illuminate\Contracts\Foundation\Application $app)
 * @method static \Illuminate\Contracts\Auth\UserProvider|null                              createUserProvider(string|null $provider = null)
 * @method static string                                                                    getDefaultUserProvider()
 * @method static bool                                                                      check()
 * @method static bool                                                                      guest()
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null                           user()
 * @method static int|string|null                                                           id()
 * @method static bool                                                                      validate(array $credentials = [])
 * @method static bool                                                                      hasUser()
 * @method static void                                                                      setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static bool                                                                      attempt(array $credentials = [], bool $remember = false)
 * @method static bool                                                                      once(array $credentials = [])
 * @method static void                                                                      login(\Illuminate\Contracts\Auth\Authenticatable $user, bool $remember = false)
 * @method static \Illuminate\Contracts\Auth\Authenticatable|bool                           loginUsingId(mixed $id, bool $remember = false)
 * @method static \Illuminate\Contracts\Auth\Authenticatable|bool                           onceUsingId(mixed $id)
 * @method static bool                                                                      viaRemember()
 * @method static void                                                                      logout()
 * @method static \Symfony\Component\HttpFoundation\Response|null                           basic(string $field = 'email', array $extraConditions = [])
 * @method static \Symfony\Component\HttpFoundation\Response|null                           onceBasic(string $field = 'email', array $extraConditions = [])
 * @method static bool                                                                      attemptWhen(array $credentials = [], array|callable|null $callbacks = null, bool $remember = false)
 * @method static void                                                                      logoutCurrentDevice()
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null                           logoutOtherDevices(string $password, string $attribute = 'password')
 * @method static void                                                                      attempting(mixed $callback)
 * @method static \Illuminate\Contracts\Auth\Authenticatable                                getLastAttempted()
 * @method static string                                                                    getName()
 * @method static string                                                                    getRecallerName()
 * @method static \Illuminate\Auth\SessionGuard                                             setRememberDuration(int $minutes)
 * @method static \Illuminate\Contracts\Cookie\QueueingFactory                              getCookieJar()
 * @method static void                                                                      setCookieJar(\Illuminate\Contracts\Cookie\QueueingFactory $cookie)
 * @method static \Illuminate\Contracts\Events\Dispatcher                                   getDispatcher()
 * @method static void                                                                      setDispatcher(\Illuminate\Contracts\Events\Dispatcher $events)
 * @method static \Illuminate\Contracts\Session\Session                                     getSession()
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null                           getUser()
 * @method static \Symfony\Component\HttpFoundation\Request                                 getRequest()
 * @method static \Illuminate\Auth\SessionGuard                                             setRequest(\Symfony\Component\HttpFoundation\Request $request)
 * @method static \Illuminate\Support\Timebox                                               getTimebox()
 * @method static \Illuminate\Contracts\Auth\Authenticatable                                authenticate()
 * @method static \Illuminate\Auth\SessionGuard                                             forgetUser()
 * @method static \Illuminate\Contracts\Auth\UserProvider                                   getProvider()
 * @method static void                                                                      setProvider(\Illuminate\Contracts\Auth\UserProvider $provider)
 * @method static void                                                                      macro(string $name, object|callable $macro)
 * @method static void                                                                      mixin(object $mixin, bool $replace = true)
 * @method static bool                                                                      hasMacro(string $name)
 * @method static void                                                                      flushMacros()
 */
// phpcs:enable
class Auth
{
    /**
     * Static magic method that extracts auth config values.
     *
     * @param mixed $name
     * @param mixed $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        try {
            return Facade::{$name}(...$arguments);
        } catch (BadMethodCallException $e) { /* @phpstan-ignore-line */
            if (method_exists(self::class, $name)) {
                return static::{$name}(...$arguments);
            }

            return Config::get("auth.{$name}", ...$arguments);
        }
    }

    /**
     * Get the name of the username field.
     *
     * @return string
     */
    public static function username(?string $inputName = null): string
    {
        return Config::get('auth.credentials.username', $inputName);
    }

    /**
     * Get the name of the password field.
     *
     * @return string
     */
    public static function password(?string $inputName = null): string
    {
        return Config::get('auth.credentials.password', $inputName);
    }

    /**
     * Get the organisation ID of the currently authenticated user.
     *
     * @return int
     */
    public static function organisationId(): int
    {
        return Facade::user()->getAttribute('organisation_id');
    }

    /**
     * Get the phases for the authentication request.
     *
     * @param array<string> $overrides
     *
     * @return array<string>
     */
    public static function phases(array $overrides = []): array
    {
        return Config::get('auth.phases', $overrides);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Ignite\Interfaces\Users\User       $user
     * @param \Ignite\Interfaces\Auth\Credentials $credentials
     *
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public static function validateCredentials(
        User $user,
        Credentials $credentials
    ): void {
        tap(Facade::getProvider()
            ->validateCredentials(
                $user,
                $credentials->toArray()
            ), function (bool $result): void {
                if (! $result) {
                    throw AuthenticationException::invalidCredentials();
                }
            });
    }

    /**
     * Get the name of the user model class.
     *
     * @return string
     */
    public static function userModel(): string
    {
        return call_user_func([Facade::getProvider(), 'getModel']);
    }
}
