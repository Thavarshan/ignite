<?php

namespace Ignite\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\SimpleResponse;
use Ignite\Interfaces\Users\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Symfony\Component\HttpFoundation\Response;

class UserNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function index(Request $request, User $user): Response|Responsable
    {
        $query = $user->notifications();

        if ($request->has('read')) {
            $query = $user->readNotifications();
        } elseif ($request->has('unread')) {
            $query = $user->unreadNotifications();
        }

        return SimpleResponse::dispatch($query->paginate());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                       $request
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        Request $request,
        DatabaseNotification $notification
    ): Response|Responsable {
        $notification->read()
            ? $notification->markAsUnread()
            : $notification->markAsRead();

        return SimpleResponse::dispatch($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function destroy(
        DatabaseNotification $notification
    ): Response|Responsable {
        $notification->delete();

        return SimpleResponse::dispatch();
    }
}
