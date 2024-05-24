<?php

namespace Ignite\Auth\Providers;

use Ignite\Auth\Authenticators\SessionAuthenticator;
use Ignite\Auth\Enums\Auth as AuthConfig;
use Ignite\Interfaces\Auth\Authenticator;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        $this->registerAuthGuards();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerAuthenticators();
    }

    /**
     * Register the application's authenticators.
     *
     * @return void
     */
    public function registerAuthenticators(): void
    {
        $this->app->singleton(Authenticator::class, SessionAuthenticator::class);
    }

    /**
     * Register the application's authentication / authorization services.
     *
     * @return void
     */
    public function registerAuthGuards(): void
    {
        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard(AuthConfig::DEFAULT_GUARD->value);
        });
    }
}
