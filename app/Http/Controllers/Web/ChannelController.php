<?php

namespace App\Http\Controllers\Web;

use App\Filters\ChannelFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ChannelFilter $filter): Response
    {
        Gate::authorize('viewAny', Channel::class);

        $channels = Channel::query()
            ->withCount('threads')
            ->filter($filter)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('channels/Index', [
            'channels' => ChannelResource::collection($channels)->response()->getData(true),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Channel::class);

        return Inertia::render('channels/Create', [
            'channel' => ChannelResource::make(new Channel)->resolve(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request): RedirectResponse
    {
        $channel = Channel::query()->create($request->validated());

        return to_route('channels.show', $channel);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Channel $channel): Response
    {
        Gate::authorize('view', $channel);

        $threads = Thread::query()
            ->where('channel_id', $channel->id)
            ->with(['channel:id,name,slug', 'user:id,name'])
            ->orderByDesc('pinned')
            ->orderByDesc('last_posted_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('channels/Show', [
            'channel' => ChannelResource::make($channel)->resolve(),
            'threads' => ThreadResource::collection($threads)->response()->getData(true),
            'filters' => $request->only(['sort_by', 'search']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Channel $channel): Response
    {
        Gate::authorize('update', $channel);

        return Inertia::render('channels/Edit', [
            'channel' => ChannelResource::make($channel)->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel): RedirectResponse
    {
        $channel->update($request->validated());

        return to_route('channels.show', $channel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Channel $channel): RedirectResponse
    {
        Gate::authorize('delete', $channel);

        $channel->delete();

        return to_route('channels.index');
    }
}
