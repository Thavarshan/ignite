<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RoleResponse as RoleResponseInterface;
use Inertia\Inertia;

class RolesViewResponse extends Response implements RoleResponseInterface
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
        return Inertia::render('Roles/Index', [
            'roles' => $this->getContent(),
        ]);
    }
}
