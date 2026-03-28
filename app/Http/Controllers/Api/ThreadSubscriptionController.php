<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    /**
     * Subscribe the authenticated user to a thread.
     */
    public function store(Request $request, Thread $thread): ThreadResource
    {
        $thread->subscribe($request->user());

        return ThreadResource::make($thread->fresh());
    }

    /**
     * Unsubscribe the authenticated user from a thread.
     */
    public function destroy(Request $request, Thread $thread): ThreadResource
    {
        $thread->unsubscribe($request->user());

        return ThreadResource::make($thread->fresh());
    }
}
