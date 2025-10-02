<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;
use Illuminate\Http\RedirectResponse;

class ReplyController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Reply $reply): RedirectResponse
    {
        $reply->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        $reply->delete();

        return back();
    }
}
