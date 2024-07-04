<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UsersCollectionResponse as UsersCollectionResponseInterface;

class UsersCollectionResponse extends Response implements UsersCollectionResponseInterface
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
        return $this->renderView();
    }
}
