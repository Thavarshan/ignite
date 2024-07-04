<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Ignite\Interfaces\Users\User;
use Illuminate\Http\Request;

class PrepareAuthenticatedSession extends AuthAction
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
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $this->limiter->clear($request);

        tap($request->getAuthUser(), function (User $user) {
            $user->fireLoggedInEvent();
        });

        return $next($request);
    }
}
