<?php

namespace App\Http\Controllers\Web;

use App\Filters\ThreadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Jobs\CreateNewThread;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ThreadController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ThreadFilter $filter, ?Channel $channel): Response
    {
        $threads = Thread::filter($filter)
            ->when($channel?->exists, function ($query) use ($channel): void {
                $query->where('channel_id', $channel->id);
            })
            ->with(['author', 'channel'])
            ->latest()
            ->paginate();

        return Inertia::render('threads/Index', [
            'threads' => ThreadResource::collection($threads),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('threads/Create', [
            'channels' => Channel::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request): SymfonyResponse
    {
        dispatch(new CreateNewThread($request->validated()))->afterResponse();

        return Inertia::location(route('threads.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread): Response
    {
        return Inertia::render('threads/Show', [
            'thread' => new ThreadResource($thread->load([
                'author',
                'channel',
                'replies',
                'replies.author',
            ])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread): Response
    {
        return Inertia::render('threads/Edit', [
            'channels' => Channel::all(),
            'thread' => new ThreadResource($thread->load(['author', 'channel'])),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Thread $thread): RedirectResponse
    {
        $thread->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thread $thread): SymfonyResponse
    {
        $thread->delete();

        return Inertia::location(route('threads.index'));
    }
}
