<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RoleResponse as RoleResponseInterface;
use Ignite\Shared\Enums\Resource;

class RoleResponse extends Response implements RoleResponseInterface
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
                'resource' => Resource::ROLES->value,
            ])
        );
    }
}
