<?php

namespace Ignite\Auth\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\LogoutResponse as LogoutResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LogoutResponse extends Response implements LogoutResponseInterface
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
        return $this->redirectToIntended('/', SymfonyResponse::HTTP_SEE_OTHER);
    }
}
