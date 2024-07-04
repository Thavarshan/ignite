<?php

namespace Ignite\Search\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Interfaces\Search\PerformsSearches;
use Ignite\Search\Http\Requests\SearchRequest;
use Ignite\Search\Http\Responses\SearchResponse;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Ignite\Search\Http\Requests\SearchRequest $request
     * @param \Ignite\Interfaces\Search\PerformsSearches $searcher
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function __invoke(
        SearchRequest $request,
        PerformsSearches $searcher
    ): Response|Responsable {
        $searcher->scopeToUser($request->user());

        $results = $searcher->search(
            $request->getQueryString() ?? '',
            $request->boolean('paginate', true)
        );

        return SearchResponse::dispatch($results);
    }
}
