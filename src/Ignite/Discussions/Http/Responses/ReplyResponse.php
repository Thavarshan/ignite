<?php

namespace Ignite\Discussions\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Discussions\ReplyResponse as ReplyResponseInterface;
use Ignite\Shared\Enums\Resource;

class ReplyResponse extends Response implements ReplyResponseInterface
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
            $this->bannerMessage($request, Resource::REPLIES->value)
        );
    }
}
