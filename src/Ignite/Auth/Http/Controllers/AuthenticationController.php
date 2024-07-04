<?php

namespace Ignite\Auth\Http\Controllers;

use App\Enums\ControllerMethod;
use App\Http\Controllers\Controller;
use App\Http\Middleware\BlockJsonRequests;
use Ignite\Auth\Http\Requests\AuthenticationRequest;
use Ignite\Auth\Http\Requests\UnauthenticateRequest;
use Ignite\Auth\Http\Responses\AuthenticatedResponse;
use Ignite\Auth\Http\Responses\LoginViewResponse;
use Ignite\Auth\Http\Responses\UnauthenticatedResponse;
use Ignite\Interfaces\Auth\AuthenticatesUsers;
use Ignite\Interfaces\Auth\UnauthenticatesUsers;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Login view cannot be allowed to be accessed by JSON requests.
        $this->middleware(BlockJsonRequests::class)->only(ControllerMethod::CREATE->value);
    }

    /**
     * Show the login view.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Symfony\Component\HttpFoundation\Response
     */
    public function create(): Responsable|Response
    {
        return LoginViewResponse::dispatch();
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param \Ignite\Auth\Http\Requests\AuthenticationRequest $request
     * @param \Ignite\Interfaces\Auth\AuthenticatesUsers       $auth
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Symfony\Component\HttpFoundation\Response
     */
    public function store(
        AuthenticationRequest $request,
        AuthenticatesUsers $auth
    ): Response|Responsable {
        return AuthenticatedResponse::dispatch($auth->login($request));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Ignite\Auth\Http\Requests\UnauthenticateRequest $request
     * @param \Ignite\Interfaces\Auth\UnauthenticatesUsers     $auth
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(
        UnauthenticateRequest $request,
        UnauthenticatesUsers $auth
    ): Responsable|Response {
        $auth->logout($request);

        return UnauthenticatedResponse::dispatch(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
