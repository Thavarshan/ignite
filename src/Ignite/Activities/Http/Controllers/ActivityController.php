<?php

namespace Ignite\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Activities\Http\Requests\FecthActivityRequest;
use Ignite\Activities\Http\Responses\ActivityCollectionResponse;
use Ignite\Interfaces\Activities\ActivityFilter;
use Ignite\Interfaces\Activities\ActivityService;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Ignite\Activities\Http\Requests\FecthActivityRequest $request
     * @param \Ignite\Interfaces\Activities\ActivityFilter          $filter
     * @param \Ignite\Interfaces\Activities\ActivityService         $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(
        FecthActivityRequest $request,
        ActivityFilter $filter,
        ActivityService $service
    ): Response {
        return ActivityCollectionResponse::dispatch($service->feed(
            $filter,
            $request->forUser(),
            $request->paginated()
        ));
    }
}
