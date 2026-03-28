<?php

namespace App\Http\Controllers\Api;

use App\Filters\ThreadFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ThreadFilter $filter): AnonymousResourceCollection
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

        return ThreadResource::collection($threads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request): JsonResponse
    {
        $thread = Thread::query()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'last_posted_at' => now(),
        ]);

        return ThreadResource::make($thread->load(['channel:id,name,slug', 'user:id,name']))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Thread $thread): ThreadResource
    {
        Gate::authorize('view', $thread);

        $thread->recordVisit();

        return ThreadResource::make($thread->load(['channel:id,name,slug', 'user:id,name']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Thread $thread): ThreadResource
    {
        $thread->update($request->validated());

        return ThreadResource::make($thread->refresh()->load(['channel:id,name,slug', 'user:id,name']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Thread $thread): Response
    {
        Gate::authorize('delete', $thread);

        $thread->delete();

        return response()->noContent();
    }

    /**
     * Restore a soft-deleted thread.
     */
    public function restore(Request $request, Thread $thread): ThreadResource
    {
        Gate::authorize('restore', $thread);

        $thread->restore();

        return ThreadResource::make($thread->load(['channel:id,name,slug', 'user:id,name']));
    }

    /**
     * Permanently delete a thread.
     */
    public function forceDelete(Request $request, Thread $thread): Response
    {
        Gate::authorize('forceDelete', $thread);

        $thread->forceDelete();

        return response()->noContent();
    }
}
