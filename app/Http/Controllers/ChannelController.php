<?php

namespace App\Http\Controllers;

use App\Filters\ChannelFilter;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChannelFilter $filter): AnonymousResourceCollection
    {
        $channels = Channel::filter($filter)->get();

        return ChannelResource::collection($channels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request): JsonResource
    {
        $channel = Channel::create($request->validated());

        return new ChannelResource($channel);
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel): JsonResource
    {
        $channel->load('threads');

        return new ChannelResource($channel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel): JsonResource
    {
        $channel->update($request->validated());

        return new ChannelResource($channel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel): SymfonyResponse
    {
        $channel->delete();

        return response()->noContent();
    }
}
