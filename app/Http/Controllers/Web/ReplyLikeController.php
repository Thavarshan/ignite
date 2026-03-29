<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReplyLikeController extends Controller
{
    public function store(Request $request, Thread $thread, Reply $reply): RedirectResponse
    {
        abort_unless($reply->thread_id === $thread->id, 422, 'Reply does not belong to this thread.');
        abort_if($reply->likedBy($request->user()), 422, 'You have already liked this reply.');

        $reply->like($request->user());

        return back();
    }

    public function destroy(Request $request, Thread $thread, Reply $reply): RedirectResponse
    {
        abort_unless($reply->thread_id === $thread->id, 422, 'Reply does not belong to this thread.');

        $reply->unlike($request->user());

        return back();
    }
}
