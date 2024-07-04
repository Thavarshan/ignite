<?php

namespace Ignite\Tasks\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Tasks\TasksCollectionResponse as TasksCollectionResponseInterface;

class TasksCollectionResponse extends Response implements TasksCollectionResponseInterface
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
