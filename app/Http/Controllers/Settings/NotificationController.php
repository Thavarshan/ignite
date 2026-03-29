<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\NotificationUpdateRequest;
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

    public function update(NotificationUpdateRequest $request): RedirectResponse
    {
        $request->user()->update([
            'notification_preferences' => $request->validated(),
        ]);

        return to_route('notifications.edit');
    }
}
