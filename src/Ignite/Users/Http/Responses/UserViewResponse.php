<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserResponse as UserResponseInterface;

class UserViewResponse extends Response implements UserResponseInterface
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
        $this->content = null;

        return $this->renderView('Profile/Show');
    }
}
