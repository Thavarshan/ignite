<?php

namespace App\Http\Controllers\Web;

use App\Filters\ThreadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Like;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ThreadFilter $filter): Response
    {
        Gate::authorize('viewAny', Thread::class);

        $query = Thread::query()
            ->with(['channel:id,name,slug', 'user:id,name'])
            ->filter($filter);

        if (! $request->filled('sort_by')) {
            $query->orderByDesc('pinned')
                ->orderByDesc('last_posted_at')
                ->orderByDesc('created_at');
        }

        $threads = $query
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('threads/Index', [
            'threads' => ThreadResource::collection($threads)->response()->getData(true),
            'channels' => ChannelResource::collection(Channel::query()->orderBy('name')->get())->resolve(),
            'filters' => $request->only(['channel', 'sort_by', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Thread::class);

        $channels = Channel::query()->orderBy('name')->get();

        return Inertia::render('threads/Create', [
            'thread' => ThreadResource::make(new Thread)->resolve(),
            'channels' => ChannelResource::collection($channels)->resolve(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request): RedirectResponse
    {
        $thread = Thread::query()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'last_posted_at' => now(),
        ]);

        return to_route('threads.show', $thread);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Thread $thread): Response
    {
        Gate::authorize('view', $thread);

        $thread->recordVisit();

        $thread->load(['channel:id,name,slug', 'user:id,name']);

        $replies = Reply::query()
            ->where('thread_id', $thread->id)
            ->whereNull('parent_id')
            ->with(['user:id,name', 'children' => fn ($q) => $q->with('user:id,name')->orderBy('created_at')])
            ->orderBy('created_at')
            ->get();

        $user = $request->user();

        if ($user) {
            $allReplyIds = $replies->pluck('id')
                ->merge($replies->flatMap(fn ($r) => $r->children->pluck('id')))
                ->all();

            $likedReplyIds = Like::query()
                ->where('user_id', $user->id)
                ->where('likeable_type', Reply::class)
                ->whereIn('likeable_id', $allReplyIds)
                ->pluck('likeable_id')
                ->all();

            $markLiked = function ($replies) use ($likedReplyIds, &$markLiked) {
                foreach ($replies as $reply) {
                    $reply->is_liked = in_array($reply->id, $likedReplyIds);

                    if ($reply->relationLoaded('children')) {
                        $markLiked($reply->children);
                    }
                }
            };

            $markLiked($replies);
        }

        return Inertia::render('threads/Show', [
            'thread' => ThreadResource::make($thread)->resolve(),
            'replies' => ReplyResource::collection($replies)->resolve(),
            'isSubscribed' => $user ? $thread->isSubscribedTo($user) : false,
            'isLiked' => $user ? $thread->likedBy($user) : false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Thread $thread): Response
    {
        Gate::authorize('update', $thread);

        $channels = Channel::query()->orderBy('name')->get();

        return Inertia::render('threads/Edit', [
            'thread' => ThreadResource::make($thread->load(['channel:id,name,slug', 'user:id,name']))->resolve(),
            'channels' => ChannelResource::collection($channels)->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Thread $thread): RedirectResponse
    {
        $thread->update($request->validated());

        return to_route('threads.show', $thread->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Thread $thread): RedirectResponse
    {
        Gate::authorize('delete', $thread);

        $thread->delete();

        return to_route('threads.index');
    }
}
