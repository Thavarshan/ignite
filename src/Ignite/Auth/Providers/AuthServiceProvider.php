<?php

namespace Ignite\Auth\Providers;

use Ignite\Auth\Authenticators\SessionAuthenticator;
use Ignite\Interfaces\Auth\Authenticator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(Authenticator::class, SessionAuthenticator::class);
    }
}
