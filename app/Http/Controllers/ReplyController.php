<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;

class ReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Channel $channel, Thread $thread)
    {
        $reply = $thread->addReply($request->user(), $request->validated());

        return back()->banner('Reply added');
    }

    /**
     * Update the specified resource in storage.
     */
public function update(UpdateReplyRequest $request, Channel $channel, Thread $thread, Reply $reply)
    {
        $reply->update($request->validated());

        return back()->banner('Reply updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel, Thread $thread, Reply $reply)
    {
        $reply->delete();

        return back()->banner('Reply deleted');
    }
}
