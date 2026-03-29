<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThreadLikeController extends Controller
{
    public function store(Request $request, Thread $thread): RedirectResponse
    {
        abort_if($thread->likedBy($request->user()), 422, 'You have already liked this thread.');

        $thread->like($request->user());

        return back();
    }

    public function destroy(Request $request, Thread $thread): RedirectResponse
    {
        $thread->unlike($request->user());

        return back();
    }
}
