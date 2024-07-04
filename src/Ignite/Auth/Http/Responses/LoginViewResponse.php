<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\LoginViewResponse as LoginViewResponseInterface;

class LoginViewResponse extends Response implements LoginViewResponseInterface
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
        return $this->renderView('Auth/Login');
    }
}
