<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LockoutResponse;

class EnsureLoginIsNotThrottled extends AuthAction
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
        if (! $this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        return $this->make(LockoutResponse::class);
    }
}
