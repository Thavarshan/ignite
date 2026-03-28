<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyLikeController extends Controller
{
    /**
     * Like a reply.
     */
    public function store(Request $request, Reply $reply): ReplyResource
    {
        abort_if($reply->likedBy($request->user()), 422, 'You have already liked this reply.');

        $reply->like($request->user());

        return ReplyResource::make($reply->fresh());
    }

    /**
     * Remove a like from a reply.
     */
    public function destroy(Request $request, Reply $reply): ReplyResource
    {
        $reply->unlike($request->user());

        return ReplyResource::make($reply->fresh());
    }
}
