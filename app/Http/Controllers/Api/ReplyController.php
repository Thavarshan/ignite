<?php

namespace App\Http\Controllers\Api;

use App\Filters\ReplyFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function index(Request $request, Thread $thread, ReplyFilter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Reply::class);

        $replies = Reply::query()
            ->where('thread_id', $thread->id)
            ->whereNull('parent_id')
            ->with(['user:id,name', 'children.user:id,name'])
            ->filter($filter)
            ->orderBy('created_at')
            ->paginate(20)
            ->withQueryString();

        return ReplyResource::collection($replies);
    }

    public function store(StoreReplyRequest $request, Thread $thread): JsonResponse
    {
        $reply = Reply::query()->create([
            ...$request->validated(),
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
        ]);

        return ReplyResource::make($reply->load('user:id,name'))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateReplyRequest $request, Thread $thread, Reply $reply): ReplyResource
    {
        $reply->update($request->validated());

        return ReplyResource::make($reply->refresh()->load('user:id,name'));
    }

    public function destroy(Request $request, Thread $thread, Reply $reply): Response
    {
        Gate::authorize('delete', $reply);

        $reply->delete();

        return response()->noContent();
    }

    /**
     * Restore a soft-deleted reply.
     */
    public function restore(Request $request, Thread $thread, Reply $reply): ReplyResource
    {
        Gate::authorize('restore', $reply);

        $reply->restore();

        return ReplyResource::make($reply->load('user:id,name'));
    }

    /**
     * Permanently delete a reply.
     */
    public function forceDelete(Request $request, Thread $thread, Reply $reply): Response
    {
        Gate::authorize('forceDelete', $reply);

        $reply->forceDelete();

        return response()->noContent();
    }
}
