<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    public function store(Request $request, Thread $thread): RedirectResponse
    {
        $thread->subscribe($request->user());

        return back();
    }

    public function destroy(Request $request, Thread $thread): RedirectResponse
    {
        $thread->unsubscribe($request->user());

        return back();
    }
}
