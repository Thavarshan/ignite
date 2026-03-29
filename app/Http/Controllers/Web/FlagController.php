<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlagRequest;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;

class FlagController extends Controller
{
    public function flagThread(StoreFlagRequest $request, Thread $thread): RedirectResponse
    {
        abort_if($thread->flaggedBy($request->user()), 422, 'You have already flagged this thread.');
        abort_if($thread->user_id === $request->user()->id, 422, 'You cannot flag your own thread.');

        $thread->flags()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return back();
    }

    public function flagReply(StoreFlagRequest $request, Thread $thread, Reply $reply): RedirectResponse
    {
        abort_unless($reply->thread_id === $thread->id, 422, 'Reply does not belong to this thread.');
        abort_if($reply->flaggedBy($request->user()), 422, 'You have already flagged this reply.');
        abort_if($reply->user_id === $request->user()->id, 422, 'You cannot flag your own reply.');

        $reply->flags()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return back();
    }
}
