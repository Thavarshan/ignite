<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlagResource;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    /**
     * Flag a thread for moderation.
     */
    public function flagThread(Request $request, Thread $thread): FlagResource
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'in:spam,inappropriate,off-topic,other'],
            'description' => ['nullable', 'string'],
        ]);

        abort_if($thread->flaggedBy($request->user()), 422, 'You have already flagged this thread.');
        abort_if($thread->user_id === $request->user()->id, 422, 'You cannot flag your own thread.');

        $flag = $thread->flags()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return FlagResource::make($flag);
    }

    /**
     * Flag a reply for moderation.
     */
    public function flagReply(Request $request, Reply $reply): FlagResource
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'in:spam,inappropriate,off-topic,other'],
            'description' => ['nullable', 'string'],
        ]);

        abort_if($reply->flaggedBy($request->user()), 422, 'You have already flagged this reply.');
        abort_if($reply->user_id === $request->user()->id, 422, 'You cannot flag your own reply.');

        $flag = $reply->flags()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return FlagResource::make($flag);
    }
}
