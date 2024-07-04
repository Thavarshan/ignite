<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Auth\Exceptions\AuthenticationException;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;

class AbortIfLocked extends AuthAction
{
    /**
     * Handle the incoming request.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest|\Illuminate\Http\Request $request
     * @param Closure                                                                $next
     *
     * @return mixed
     */
    public function handle(
        AuthenticationRequest|Request $request,
        Closure $next
    ): mixed {
        $user = $this->getAttemptingUser() ?? $this->validateCredentials($request);

        if ($user->isLocked()) {
            event(new Lockout($request));

            throw AuthenticationException::accountLocked($user);
        }

        return $next($request);
    }
}
