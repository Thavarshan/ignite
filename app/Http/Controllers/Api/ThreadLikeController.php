<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadLikeController extends Controller
{
    /**
     * Like a thread.
     */
    public function store(Request $request, Thread $thread): ThreadResource
    {
        abort_if($thread->likedBy($request->user()), 422, 'You have already liked this thread.');

        $thread->like($request->user());

        return ThreadResource::make($thread->fresh());
    }

    /**
     * Remove a like from a thread.
     */
    public function destroy(Request $request, Thread $thread): ThreadResource
    {
        $thread->unlike($request->user());

        return ThreadResource::make($thread->fresh());
    }
}
