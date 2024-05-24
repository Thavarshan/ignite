<?php

namespace Ignite\Auth\Responses;

use App\Http\Responses\Response;
use Ignite\Auth\Enums\Auth;
use Ignite\Interfaces\Auth\LoginResponse as LoginResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LoginResponse extends Response implements LoginResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Inertia\Response
     */
    public function toResponse($request)
    {
        return $this->redirectToRoute(
            Auth::HOME->value,
            [],
            SymfonyResponse::HTTP_SEE_OTHER
        );
    }
}
