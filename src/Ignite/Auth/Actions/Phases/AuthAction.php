<?php

namespace Ignite\Auth\Actions\Phases;

use App\Support\InteractsWithContainer;
use Ignite\Auth\Exceptions\AuthenticationException;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\LoginRateLimiter;

abstract class AuthAction
{
    use InteractsWithContainer;

    /**
     * The user attempting authentication.
     *
     * @var \Ignite\Interfaces\Users\User|null
     */
    protected ?User $attemptingUser = null;

    /**
     * Create a new auth action instance.
     *
     * @param \Laravel\Fortify\LoginRateLimiter        $limiter
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     * @param \Ignite\Interfaces\Users\UserService     $users
     *
     * @return void
     */
    public function __construct(
        protected readonly LoginRateLimiter $limiter,
        protected readonly StatefulGuard $guard,
        protected readonly UserService $users,
    ) {
    }

    /**
     * Get the user attempting authentication.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function getAttemptingUser(): ?User
    {
        return $this->attemptingUser;
    }

    /**
     * Attempt to validate the incoming credentials.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest $request
     *
     * @return \Ignite\Interfaces\Users\User|null
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Ignite\Auth\Exceptions\AuthenticationException
     */
    protected function validateCredentials(AuthenticationRequest $request): ?User
    {
        try {
            /** @var \Ignite\Interfaces\Users\User $user */
            $user = $this->users->findBy(
                Auth::username(),
                $request->username()
            );

            Auth::validateCredentials($user, $request->getCredentials());

            $this->attemptingUser = $user;

            return $user;
        } catch (ModelNotFoundException $e) {
            $this->fireFailedEvent($request);

            $this->throwFailedAuthenticationException($request);
        } catch (AuthenticationException $e) {
            $this->fireFailedEvent($request);

            throw $e;
        }

        return null;
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest|\Illuminate\Http\Request $request
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException(
        AuthenticationRequest|Request $request
    ): void {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            Auth::username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest                                 $request
     * @param \Illuminate\Contracts\Auth\Authenticatable|\Ignite\Interfaces\Users\User|null $user
     *
     * @return void
     */
    protected function fireFailedEvent(
        AuthenticationRequest $request,
        Authenticatable|User|null $user = null
    ): void {
        event(new Failed(config('fortify.guard'), $user, [
            Auth::username() => $request->username(),
            'password' => $request->password(),
        ]));
    }
}
