<?php

namespace Ignite\Auth\Http\Controllers;

use App\Enums\ControllerMethod;
use App\Http\Middleware\BlockJsonRequests;
use Ignite\Auth\Http\Responses\RegisterResponse;
use Ignite\Auth\Http\Responses\RegisterViewResponse;
use Ignite\Interfaces\Users\CreatesNewUsers;
use Ignite\Users\Http\Controllers\UserController;
use Ignite\Users\Http\Requests\StoreUserRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserController extends UserController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Registration view cannot be allowed to be accessed by JSON requests.
        $this->middleware(BlockJsonRequests::class)->only(ControllerMethod::CREATE->value);
    }

    /**
     * Show the registration view.
     *
     * @param string|null $email
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable
     */
    public function create(?string $email = null): Response|Responsable
    {
        return RegisterViewResponse::dispatch(compact('email'));
    }

    /**
     * Register a new user.
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
        parent::store($request, $creator);

        event(new Registered($user = $creator->getUser()));

        app(StatefulGuard::class)->login($user);

        return RegisterResponse::dispatch($user, Response::HTTP_CREATED);
    }
}
