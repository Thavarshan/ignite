<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController as Controller;

class UserProfileController extends Controller
{
    /**
     * Show the general profile settings screen.
     */
    public function show(Request $request)
    {
        parent::show($request);

        return Inertia::render('profile/show');
    }

    /**
     * Show the general profile settings screen.
     */
    public function edit(Request $request)
    {
        return Inertia::render('profile/edit', [
            'user' => $request->user(),
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                'confirm'
            ),
            'sessions' => $this->sessions($request)->all(),
        ]);
    }
}
