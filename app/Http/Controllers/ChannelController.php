<?php

namespace App\Http\Controllers;

use App\Filters\ChannelFilter;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class ChannelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(ChannelFilter $filter)
    {
        $this->authorize('viewAny', Channel::class);

        return Inertia::render('channles/index', [
            'channels' => fn () => Channel::filter($filter)->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {
        Channel::create($request->validated());

        return redirect()
            ->route('channels.index')
            ->with('success', 'Channel created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $channel->update($request->validated());

        return redirect()
            ->route('channels.index')
            ->with('success', 'Channel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        $this->authorize('delete', $channel);

        $channel->delete();

        return redirect()
            ->route('channels.index')
            ->with('success', 'Channel deleted successfully.');
    }
}
