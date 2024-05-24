<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserResponse as UserResponseInterface;
use Ignite\Shared\Enums\Resource;

class UserResponse extends Response implements UserResponseInterface
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
        return $this->back()->banner(
            $this->bannerMessage($request, Resource::USERS->value)
        );
    }
}
