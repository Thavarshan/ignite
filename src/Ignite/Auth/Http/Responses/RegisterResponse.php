<?php

namespace Ignite\Auth\Http\Responses;

use App\Enums\Route;
use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RegisterResponse as RegisterResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RegisterResponse extends Response implements RegisterResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->redirectTo(
            Route::HOME->value,
            SymfonyResponse::HTTP_SEE_OTHER
        );
    }
}
