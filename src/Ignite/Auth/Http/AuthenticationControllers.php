<?php

namespace Ignite\Auth\Http;

use App\Http\Controllers\Controller;
use Ignite\Auth\Requests\LoginRequest;
use Ignite\Auth\Responses\LoginResponse;
use Ignite\Auth\Responses\LoginViewResponse;
use Ignite\Auth\Responses\LogoutResponse;
use Ignite\Interfaces\Auth\Authenticator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationControllers extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create(): InertiaResponse
    {
        return LoginViewResponse::dispatch(viewName: 'auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \Ignite\Auth\Requests\LoginRequest    $request
     * @param \Ignite\Interfaces\Auth\Authenticator $authenticator
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(
        LoginRequest $request,
        Authenticator $authenticator
    ): RedirectResponse|JsonResponse {
        $user = $authenticator->login(
            email: $request->input('email'),
            password: $request->input('password')
        );

        return LoginResponse::dispatch(content: compact('user'));
    }

    /**
     * Log the user out of the application.
     *
     * @param \Ignite\Interfaces\Auth\Authenticator $authenticator
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Authenticator $authenticator): RedirectResponse
    {
        $authenticator->logout();

        return LogoutResponse::dispatch(null, Response::HTTP_NO_CONTENT);
    }
}
