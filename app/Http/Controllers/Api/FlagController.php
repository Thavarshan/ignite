<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlagRequest;
use App\Http\Resources\FlagResource;
use App\Models\Reply;
use App\Models\Thread;

class FlagController extends Controller
{
    /**
     * Flag a thread for moderation.
     */
    public function flagThread(StoreFlagRequest $request, Thread $thread): FlagResource
    {
        abort_if($thread->flaggedBy($request->user()), 422, 'You have already flagged this thread.');
        abort_if($thread->user_id === $request->user()->id, 422, 'You cannot flag your own thread.');

        $flag = $thread->flags()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return FlagResource::make($flag);
    }

    /**
     * Flag a reply for moderation.
     */
    public function flagReply(StoreFlagRequest $request, Reply $reply): FlagResource
    {
        abort_if($reply->flaggedBy($request->user()), 422, 'You have already flagged this reply.');
        abort_if($reply->user_id === $request->user()->id, 422, 'You cannot flag your own reply.');

        $flag = $reply->flags()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return FlagResource::make($flag);
    }
}
