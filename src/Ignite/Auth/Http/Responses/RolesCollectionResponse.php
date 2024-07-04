<?php

namespace Ignite\Auth\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Auth\RolesCollectionResponse as RolesCollectionResponseInterface;
use Inertia\Inertia;

class RolesCollectionResponse extends Response implements RolesCollectionResponseInterface
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
