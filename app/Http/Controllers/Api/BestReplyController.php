<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Models\Reply;
use App\Models\Thread;
use App\Notifications\BestAnswerMarked;
use Illuminate\Support\Facades\Gate;

class BestReplyController extends Controller
{
    /**
     * Mark a reply as the best answer for a thread.
     */
    public function store(Thread $thread, Reply $reply): ThreadResource
    {
        Gate::authorize('markBestReply', $thread);

        abort_unless($reply->thread_id === $thread->id, 422, 'Reply does not belong to this thread.');

        $thread->markBestReply($reply);

        if ($reply->user_id !== $thread->user_id) {
            $reply->user->notify(new BestAnswerMarked($thread, $reply));
        }

        return ThreadResource::make($thread->fresh());
    }

    /**
     * Remove the best answer designation from a thread.
     */
    public function destroy(Thread $thread): ThreadResource
    {
        Gate::authorize('markBestReply', $thread);

        $thread->unmarkBestReply();

        return ThreadResource::make($thread->fresh());
    }
}
