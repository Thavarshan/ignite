<?php

namespace Ignite\Search\Http\Responses;

use App\Http\Responses\Response;
use Illuminate\Contracts\Support\Responsable;

class SearchResponse extends Response implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toResponse($request)
    {
        return $this->renderView('Search/Index');
    }
}
