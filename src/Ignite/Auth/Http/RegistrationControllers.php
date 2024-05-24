<?php

namespace Ignite\Auth\Http;

use App\Http\Controllers\Controller;
use Ignite\Auth\Responses\RegisterResponse;
use Ignite\Auth\Responses\RegisterViewResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RegistrationControllers extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Inertia\Response
     */
    public function create(): InertiaResponse
    {
        return RegisterViewResponse::dispatch(viewName: 'auth.register');
    }

    public function store()
    {
        // Validate the request...
        $validated = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Create the user...
        RegisterResponse::dispatch();
    }
}
