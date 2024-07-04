<?php

namespace Ignite\Statuses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\SimpleResponse;
use Ignite\Statuses\Enums\Status;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function __invoke(): Response|Responsable
    {
        return SimpleResponse::dispatch(['data' => Status::all()]);
    }
}
