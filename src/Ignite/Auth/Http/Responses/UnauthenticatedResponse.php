<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\UnauthenticatedResponse as UnauthenticatedResponseInterface;

class UnauthenticatedResponse extends Response implements UnauthenticatedResponseInterface
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
        return $this->redirectToRoute('login');
    }
}
