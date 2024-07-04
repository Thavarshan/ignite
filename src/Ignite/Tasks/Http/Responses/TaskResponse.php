<?php

namespace Ignite\Tasks\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Tasks\TaskResponse as TaskResponseInterface;
use Ignite\Shared\Enums\Resource;

class TaskResponse extends Response implements TaskResponseInterface
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
            $this->bannerMessage($request, Resource::TASKS->value)
        );
    }
}
