<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\AccountLockedResponse as AccountLockedResponseInterface;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AccountLockedResponse extends Response implements AccountLockedResponseInterface
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
        throw ValidationException::withMessages([
            Auth::username() => [__('messages.auth.locked')],
        ])->status(SymfonyResponse::HTTP_UNAUTHORIZED);
    }
}
