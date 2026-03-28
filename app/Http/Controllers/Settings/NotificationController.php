<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Notifications', [
            'preferences' => $request->user()->notification_preferences ?? [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thread_replied' => ['required', 'boolean'],
            'best_answer_marked' => ['required', 'boolean'],
            'user_mentioned' => ['required', 'boolean'],
            'reply_added' => ['required', 'boolean'],
        ]);

        $request->user()->update([
            'notification_preferences' => $data,
        ]);

        return to_route('notifications.edit');
    }
}
