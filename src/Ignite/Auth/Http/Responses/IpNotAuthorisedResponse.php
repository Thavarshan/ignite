<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Auth\Facades\Auth;
use Ignite\Interfaces\Auth\IpNotAuthorisedResponse as IpNotAuthorisedResponseInterface;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class IpNotAuthorisedResponse extends Response implements IpNotAuthorisedResponseInterface
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
            Auth::username() => [__('messages.auth.ip_not_authorised')],
        ])->status(SymfonyResponse::HTTP_UNAUTHORIZED);
    }
}
