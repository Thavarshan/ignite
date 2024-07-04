<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\PermissionResponse as PermissionResponseInterface;
use Ignite\Shared\Enums\Resource;

class PermissionResponse extends Response implements PermissionResponseInterface
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
                'resource' => Resource::PERMISSIONS->value,
            ])
        );
    }
}
