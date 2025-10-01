<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplyRequest;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ThreadReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Thread $thread): RedirectResponse
    {
        DB::transaction(function () use ($request, $thread): void {
            $thread->addReply([
                'body' => $request->input('body'),
                'user_id' => $request->user()->id,
            ]);
        });

        return back();
    }
}
