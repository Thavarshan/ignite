<?php

namespace Ignite\Users\Http\Controllers;

use Ignite\Interfaces\Users\UpdatesUserInformation;
use Ignite\Interfaces\Users\User;
use Ignite\Users\Http\Requests\UpdateUserRequest;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class ProfileInformationController extends UserController
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Users\Http\Requests\UpdateUserRequest   $request
     * @param \Ignite\Interfaces\Users\UpdatesUserInformation $updater
     * @param \Ignite\Interfaces\Users\User                   $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdateUserRequest $request,
        UpdatesUserInformation $updater,
        User $user
    ): Response|Responsable {
        return parent::update($request, $updater, $request->user());
    }
}
