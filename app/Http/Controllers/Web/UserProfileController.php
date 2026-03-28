<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserProfileController extends Controller
{
    public function __invoke(User $user): Response
    {
        $user->loadCount(['threads', 'replies']);

        $threads = $user->threads()
            ->with(['channel', 'user'])
            ->latest()
            ->paginate(10);

        return Inertia::render('users/Show', [
            'profileUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'created_at' => $user->created_at->toIso8601String(),
                'threads_count' => $user->threads_count,
                'replies_count' => $user->replies_count,
            ],
            'threads' => ThreadResource::collection($threads),
        ]);
    }
}
