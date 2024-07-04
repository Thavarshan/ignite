<?php

namespace Ignite\Notes\Http\Responses;

use App\Http\Responses\Response;
use Ignite\Interfaces\Notes\NoteResponse as NoteResponseInterface;
use Ignite\Shared\Enums\Resource;

class NoteResponse extends Response implements NoteResponseInterface
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
            $this->bannerMessage($request, Resource::NOTES->value)
        );
    }
}
