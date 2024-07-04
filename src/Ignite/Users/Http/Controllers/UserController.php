<?php

namespace Ignite\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Ignite\Interfaces\Users\CreatesNewUsers;
use Ignite\Interfaces\Users\UpdatesUserInformation;
use Ignite\Interfaces\Users\User as UserInterface;
use Ignite\Interfaces\Users\UserFilter;
use Ignite\Interfaces\Users\UserService;
use Ignite\Shared\Enums\Action;
use Ignite\Users\Http\Requests\StoreUserRequest;
use Ignite\Users\Http\Requests\UpdateUserRequest;
use Ignite\Users\Http\Responses\UserResponse;
use Ignite\Users\Http\Responses\UsersCollectionResponse;
use Ignite\Users\Http\Responses\UserViewResponse;
use Ignite\Users\Jobs\DeleteUserProfile;
use Ignite\Users\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request             $request
     * @param \Ignite\Interfaces\Users\UserFilter  $filter
     * @param \Ignite\Interfaces\Users\UserService $service
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function index(
        Request $request,
        UserFilter $filter,
        UserService $service
    ): Response|Responsable {
        $this->authorize(Action::VIEW_ANY->value, User::class);

        $users = $service->list( // @phpstan-ignore-line
            $filter,
            paginated: true,
            user: $request->user(),
        )->withQueryString();

        return UsersCollectionResponse::dispatch(
            $users,
            Response::HTTP_OK,
            'Users/Index',
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Ignite\Users\Http\Requests\StoreUserRequest $request
     * @param \Ignite\Interfaces\Users\CreatesNewUsers     $creator
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function store(
        StoreUserRequest $request,
        CreatesNewUsers $creator
    ): Response|Responsable {
        $user = $creator->create($request->validated());

        return UserResponse::dispatch(
            $user,
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function show(UserInterface $user): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $user);

        $user->load(['roles'])->append(['role']);

        return UserViewResponse::dispatch(
            $user,
            Response::HTTP_OK,
            'Users/Show',
        );
    }

    /**
     * Display the specified resource edit screen.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function edit(UserInterface $user): Response|Responsable
    {
        $this->authorize(Action::VIEW->value, $user);

        $user->load(['roles'])->append(['role']);

        return UserViewResponse::dispatch(
            $user,
            Response::HTTP_OK,
            'Users/Edit',
        );
    }

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
        UserInterface $user
    ): Response|Responsable {
        $user = $updater->update($user, $request->validated());

        return UserResponse::dispatch($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(UserInterface $user): Response
    {
        $this->authorize(Action::DELETE, $user);

        DeleteUserProfile::dispatch($user);

        return UserResponse::dispatch(null, Response::HTTP_NO_CONTENT);
    }
}
