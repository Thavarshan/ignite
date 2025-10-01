<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplyRequest;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;

class ThreadReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Thread $thread): RedirectResponse
    {
        $thread->addReply([
            'body' => $request->input('body'),
            'user_id' => $request->user()->id,
        ]);

        return back();
    }
}
