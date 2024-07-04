<?php

namespace Ignite\Discussions\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Discussions\DiscussionResponse as DiscussionResponseInterface;
use Ignite\Shared\Enums\Resource;

class DiscussionResponse extends Response implements DiscussionResponseInterface
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
            $this->bannerMessage($request, Resource::DISCUSSIONS->value)
        );
    }
}
