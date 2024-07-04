<?php

namespace Ignite\Auth\Actions;

use App\Support\InteractsWithContainer;
use Ignite\Auth\Actions\Phases\AbortIfLocked;
use Ignite\Auth\Actions\Phases\AttemptToAuthenticate;
use Ignite\Auth\Actions\Phases\CanonicalizeUsername;
use Ignite\Auth\Actions\Phases\CheckIpAuthorisation;
use Ignite\Auth\Actions\Phases\EnsureLoginIsNotThrottled;
use Ignite\Auth\Actions\Phases\PrepareAuthenticatedSession;
use Ignite\Auth\Actions\Phases\RedirectIfTwoFactorAuthenticatable;
use Ignite\Auth\Actions\Phases\ValidateAttemptingUser;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\AuthenticatesUsers;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Ignite\Interfaces\Users\User;
use Illuminate\Http\RedirectResponse;

class AuthenticateUser implements AuthenticatesUsers
{
    use InteractsWithContainer;

    /**
     * The phases for the authentication request.
     *
     * @var array<string>|null
     */
    protected static $phases = [
        CanonicalizeUsername::class,
        EnsureLoginIsNotThrottled::class,
        ValidateAttemptingUser::class,
        CheckIpAuthorisation::class,
        AbortIfLocked::class,
        RedirectIfTwoFactorAuthenticatable::class,
        AttemptToAuthenticate::class,
        PrepareAuthenticatedSession::class,
    ];

    /**
     * Attempt to authenticate a new session.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest $request
     *
     * @return \Ignite\Interfaces\Users\User|\Illuminate\Http\RedirectResponse
     */
    public function login(AuthenticationRequest $request): User|RedirectResponse
    {
        return $this->pipeline()
            ->send($request)
            ->through(static::getPhases())
            ->then(function (AuthenticationRequest $request) {
                return $request->getAuthUser();
            });
    }

    /**
     * Set the phases for the authentication request.
     *
     * @param array $phases
     *
     * @return void
     */
    public static function setPhases(array $phases): void
    {
        static::$phases = array_merge(
            static::$phases ?? [],
            $phases
        );
    }

    /**
     * Get the phases for the authentication request.
     *
     * @return array
     */
    public static function getPhases(): array
    {
        return array_filter(array_merge(
            static::$phases ?? [],
            Auth::phases()
        ));
    }
}
