<?php

namespace App\Http\Controllers\Web;

use App\Filters\ThreadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Thread;
use Inertia\Inertia;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ThreadFilter $filter, Channel $channel)
    {
        $this->authorize('viewAny', Thread::class);

        return Inertia::render('threads/index', [
            'threads' => fn () => ThreadResource::collection(Thread::filter($filter)
                ->with(['user:id,name', 'channel'])
                ->where('is_ticket', false)
                ->whereNull('locked_at')
                ->latest()
                ->paginate(10)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Channel $channel)
    {
        $this->authorize('create', Thread::class);

        return Inertia::render('threads/create', [
            'channels' => fn () => ChannelResource::collection(Channel::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request, Channel $channel)
    {
        $thread = Thread::make($data = $request->validated());

        if (! isset($data['channel_id'])) {
            $thread->channel()->associate($request->channel_id);
        }

        $thread->user()->associate($request->user());

        $thread->save();

        return redirect()->route('threads.show', [$channel, $thread]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel, Thread $thread)
    {
        $this->authorize('view', $thread);

        $thread->increment('views');

        return Inertia::render('threads/show', [
            'thread' => fn () => new ThreadResource($thread->load([
                'user',
                'channel',
                // 'replies',
            ])),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Channel $channel, Thread $thread)
    {
        $thread->update($request->validated());

        return redirect()->route('threads.show', [$channel, $thread]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return redirect()->route('threads.index', $channel);
    }
}
