<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\AuthenticatedResponse as AuthenticatedResponseInterface;
use Illuminate\Http\RedirectResponse;

class AuthenticatedResponse extends Response implements AuthenticatedResponseInterface
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
        if ($this->getContent() instanceof RedirectResponse) {
            return $this->getContent();
        }

        return $this->redirectHome();
    }
}
