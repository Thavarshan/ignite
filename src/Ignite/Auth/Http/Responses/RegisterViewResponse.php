<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RegisterViewResponse as RegisterViewResponseInterface;

class RegisterViewResponse extends Response implements RegisterViewResponseInterface
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
        return $this->renderView('Auth/Register');
    }
}
