<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\Thread;
use App\Notifications\BestAnswerMarked;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class BestReplyController extends Controller
{
    public function store(Thread $thread, Reply $reply): RedirectResponse
    {
        Gate::authorize('markBestReply', $thread);

        abort_unless($reply->thread_id === $thread->id, 422, 'Reply does not belong to this thread.');

        $thread->markBestReply($reply);

        if ($reply->user_id !== $thread->user_id) {
            $reply->user->notify(new BestAnswerMarked($thread, $reply));
        }

        return redirect()->route('threads.show', $thread);
    }

    public function destroy(Thread $thread): RedirectResponse
    {
        Gate::authorize('markBestReply', $thread);

        $thread->unmarkBestReply();

        return redirect()->route('threads.show', $thread);
    }
}
