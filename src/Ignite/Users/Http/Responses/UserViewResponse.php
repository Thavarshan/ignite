<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserViewResponse as UserViewResponseInterface;

class UserViewResponse extends Response implements UserViewResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Inertia\Response
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toResponse($request)
    {
        // As the authenticated user is already available via global
        // props, we don't need to pass it to the view.
        return $this->renderView();
    }
}
