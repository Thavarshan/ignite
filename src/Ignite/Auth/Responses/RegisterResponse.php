<?php

namespace Ignite\Auth\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RegisterResponse as RegisterResponseInterface;

class RegisterResponse extends Response implements RegisterResponseInterface
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
        return $this->redirectHome();
    }
}
