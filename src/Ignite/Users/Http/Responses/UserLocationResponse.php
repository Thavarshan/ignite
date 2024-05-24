<?php

namespace Ignite\Users\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Users\UserLocationResponse as UserLocationResponseInterface;
use Ignite\Shared\Enums\Resource;

class UserLocationResponse extends Response implements UserLocationResponseInterface
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toResponse($request)
    {
        return $this->back()->banner(__('messages.resource.saved', [
            'resource' => Resource::LOCATIONS->value,
        ]));
    }
}
