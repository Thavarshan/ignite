<?php

namespace Ignite\Users\Http\Controllers;

use App\Enums\ControllerMethod;
use App\Http\Controllers\Controller;
use App\Http\Middleware\BlockJsonRequests;
use Ignite\Users\Http\Requests\DeleteCurrentUserRequest;
use Ignite\Users\Http\Requests\GetCurrentUserRequest;
use Ignite\Users\Http\Responses\UserDeletedResponse;
use Ignite\Users\Http\Responses\UserEditViewResponse;
use Ignite\Users\Http\Responses\UserViewResponse;
use Ignite\Users\Jobs\DeleteUserProfile;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class CurrentUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Edit view cannot be allowed to be accessed by JSON requests.
        $this->middleware(BlockJsonRequests::class)->only(ControllerMethod::EDIT->value);
    }

    /**
     * Display the specified resource.
     *
     * @param \Ignite\Users\Http\Requests\GetCurrentUserRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(GetCurrentUserRequest $request): Response|Responsable
    {
        return UserViewResponse::dispatch(
            $request->authUser(),
            Response::HTTP_OK,
            'Profile/Show'
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ignite\Users\Http\Requests\GetCurrentUserRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function edit(GetCurrentUserRequest $request): Response|Responsable
    {
        return UserEditViewResponse::dispatch(
            [
                'sessions' => $request->authUser()->getSessions($request),
                'confirmsTwoFactorAuthentication' => true,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Delete the current user.
     *
     * @param \Ignite\Users\Http\Requests\DeleteCurrentUserRequest $request
     * @param \Illuminate\Contracts\Auth\StatefulGuard             $guard
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(
        DeleteCurrentUserRequest $request,
        StatefulGuard $guard
    ): Response|Responsable {
        DeleteUserProfile::dispatch($request->authUser());

        $guard->logout();

        return UserDeletedResponse::dispatch(null, Response::HTTP_NO_CONTENT);
    }
}
