<?php

namespace App\Http\Middleware;

use Closure;
use Ignite\Interfaces\Users\User;
use Symfony\Component\HttpFoundation\Response;

class AuthoriseIfAdmin extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param array<string>            ...$guards
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $response = parent::handle($request, $next, ...$guards);

        return with($request->user(), function (?User $user) use ($response): mixed {
            abort_unless(
                $user && $user->isAdmin(),
                Response::HTTP_FORBIDDEN,
                'You are not authorised to access this resource.'
            );

            return $response;
        });
    }
}
