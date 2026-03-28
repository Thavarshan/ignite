<?php

namespace App\Http\Controllers\Api;

use App\Filters\ChannelFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ChannelFilter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Channel::class);

        $channels = Channel::query()
            ->filter($filter)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return ChannelResource::collection($channels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request): JsonResponse
    {
        $channel = Channel::query()->create($request->validated());

        return ChannelResource::make($channel)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Channel $channel): ChannelResource
    {
        Gate::authorize('view', $channel);

        return ChannelResource::make($channel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel): ChannelResource
    {
        $channel->update($request->validated());

        return ChannelResource::make($channel->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Channel $channel): Response
    {
        Gate::authorize('delete', $channel);

        $channel->delete();

        return response()->noContent();
    }
}
