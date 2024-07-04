<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\IpAuthorisationResponse as IpAuthorisationResponseInterface;
use Ignite\Shared\Enums\Resource;

class IpAuthorisationResponse extends Response implements IpAuthorisationResponseInterface
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
            __('messages.resource.saved', [
                'resource' => Resource::USERS->value,
            ])
        );
    }
}
