<?php

namespace Ignite\Users\Http\Controllers;

use Ignite\Interfaces\Users\UpdatesUserPasswords;
use Ignite\Users\Http\Requests\UpdatePasswordRequest;
use Ignite\Users\Http\Responses\UserResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param \Ignite\Users\Http\Requests\UpdatePasswordRequest $request
     * @param \Ignite\Interfaces\Users\UpdatesUserPasswords     $updater
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdatePasswordRequest $request,
        UpdatesUserPasswords $updater
    ): Response|Responsable {
        $updater->update(
            $user = $request->user(),
            $request->validated()
        );

        return UserResponse::dispatch($user);
    }
}
