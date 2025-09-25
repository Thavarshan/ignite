<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilter;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Thread;
use Inertia\Inertia;
use Inertia\Response;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ThreadFilter $filter, ?Channel $channel): Response
    {
        $threads = Thread::filter($filter)
            ->when($channel, fn ($query) => $query->where('channel_id', $channel->id))
            ->with(['author', 'channel'])
            ->latest()
            ->paginate();

        return Inertia::render('threads/Index', [
            'channel' => $channel,
            'threads' => ThreadResource::collection($threads),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('threads/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
