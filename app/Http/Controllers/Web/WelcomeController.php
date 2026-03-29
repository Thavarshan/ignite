<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Thread;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $latestThreads = Thread::query()
            ->with(['channel:id,name,slug', 'user:id,name'])
            ->orderByDesc('pinned')
            ->orderByDesc('last_posted_at')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $popularChannels = Channel::query()
            ->withCount('threads')
            ->orderByDesc('threads_count')
            ->limit(8)
            ->get();

        return Inertia::render('Welcome', [
            'latestThreads' => ThreadResource::collection($latestThreads)->resolve(),
            'popularChannels' => ChannelResource::collection($popularChannels)->resolve(),
        ]);
    }
}
