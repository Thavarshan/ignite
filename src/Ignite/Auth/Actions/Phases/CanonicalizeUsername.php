<?php

namespace Ignite\Auth\Actions\Phases;

use Closure;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\AuthenticationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CanonicalizeUsername
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
        $request->merge([
            Auth::username() => Str::lower($request->{Auth::username()}),
        ]);

        return $next($request);
    }
}
