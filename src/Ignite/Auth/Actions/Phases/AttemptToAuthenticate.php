<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Illuminate\Http\Request;

class AttemptToAuthenticate extends AuthAction
{
    /**
     * Handle the incoming request.
     *
     * @param \Ignite\Interfaces\Auth\AuthenticationRequest|\Illuminate\Http\Request $request
     * @param Closure                                                                $next
     *
     * @return mixed
     *
     * @throws \Ignite\Auth\Exceptions\AuthenticationException
     */
    public function handle(
        AuthenticationRequest|Request $request,
        Closure $next
    ): mixed {
        if (
            $this->guard->attempt(
                $request->getCredentials()->toArray(),
                $request->remember()
            )
        ) {
            $request->setAuthUser($this->guard->user());

            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);

        return null;
    }
}
