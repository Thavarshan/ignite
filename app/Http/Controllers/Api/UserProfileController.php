<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\ThreadResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserProfileController extends Controller
{
    /**
     * Display a user's profile.
     */
    public function show(User $user): UserProfileResource
    {
        $user->loadCount(['threads', 'replies']);

        return UserProfileResource::make($user);
    }

    /**
     * List threads created by the user.
     */
    public function threads(User $user): AnonymousResourceCollection
    {
        $threads = $user->threads()->latest()->paginate(20);

        return ThreadResource::collection($threads);
    }

    /**
     * List replies authored by the user.
     */
    public function replies(User $user): AnonymousResourceCollection
    {
        $replies = $user->replies()->latest()->paginate(20);

        return ReplyResource::collection($replies);
    }
}
