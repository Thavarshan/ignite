<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function store(StoreReplyRequest $request, Thread $thread): RedirectResponse
    {
        Reply::query()->create([
            ...$request->validated(),
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
        ]);

        return to_route('threads.show', $thread);
    }

    public function update(UpdateReplyRequest $request, Thread $thread, Reply $reply): RedirectResponse
    {
        $reply->update($request->validated());

        return to_route('threads.show', $thread);
    }

    public function destroy(Request $request, Thread $thread, Reply $reply): RedirectResponse
    {
        Gate::authorize('delete', $reply);

        $reply->delete();

        return to_route('threads.show', $thread);
    }
}
