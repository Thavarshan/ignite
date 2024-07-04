<?php

namespace Ignite\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Interfaces\Settings\SettingsFilter;
use Ignite\Interfaces\Settings\UpdatesEntitySettings;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserSettings;
use Ignite\Shared\Enums\Action;
use Ignite\Users\Http\Requests\UpdateSettingsRequest;
use Ignite\Users\Http\Responses\UserSettingsCollectionResponse;
use Ignite\Users\Http\Responses\UserSettingsResponse;
use Ignite\Users\Http\Responses\UserSettingsViewResponse;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class UserSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Ignite\Interfaces\Settings\SettingsFilter $filter
     * @param \Ignite\Interfaces\Users\User              $user
     * @param \Ignite\Interfaces\Users\UserSettings      $settings
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        SettingsFilter $filter,
        User $user,
        UserSettings $settings
    ): Response {
        $this->authorize(Action::UPDATE->value, $user);

        $settings = $settings->filtered($filter)->all();

        return UserSettingsCollectionResponse::dispatch(
            compact('settings')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function edit(User $user): Response|Responsable
    {
        return UserSettingsViewResponse::dispatch();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Ignite\Users\Http\Requests\UpdateSettingsRequest $request
     * @param \Ignite\Interfaces\Settings\UpdatesEntitySettings $updater
     * @param \Ignite\Interfaces\Users\User                     $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function update(
        UpdateSettingsRequest $request,
        UpdatesEntitySettings $updater,
        User $user
    ): Response|Responsable {
        $updater->update($user, $request->validated());

        return UserSettingsResponse::dispatch();
    }
}
