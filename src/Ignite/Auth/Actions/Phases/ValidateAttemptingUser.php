<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Illuminate\Http\Request;

class ValidateAttemptingUser extends AuthAction
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
        $this->validateCredentials($request);

        return $next($request);
    }
}
