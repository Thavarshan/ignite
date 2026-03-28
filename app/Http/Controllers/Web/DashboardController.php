<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $latestThreads = Thread::query()
            ->with(['channel:id,name,slug', 'user:id,name'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'latestThreads' => ThreadResource::collection($latestThreads)->resolve(),
            'stats' => [
                'threads_count' => Thread::count(),
                'replies_count' => Reply::count(),
                'channels_count' => Channel::count(),
            ],
        ]);
    }
}
