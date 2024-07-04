<?php

namespace App\Http\Middleware;

use Closure;
use Ignite\Interfaces\Organisations\OrganisationSettings;
use Ignite\Interfaces\Settings\SettingsService;
use Ignite\Interfaces\Users\User;
use Ignite\Interfaces\Users\UserSettings;
use Ignite\Organisations\Repositories\SettingsRepository as OrganisationSettingsRepository;
use Ignite\Users\Repositories\SettingsRepository as UserSettingsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadEntitySettings
{
    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(
        protected Application $app
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return with($request->user(), function (?User $user) use ($request, $next) {
            if (! $user) {
                return $next($request);
            }

            $this->loadCurrentUserSettings($user);
            $this->loadCurrentOrganisationSettings($user);

            return $next($request);
        });
    }

    /**
     * Save the configurations for the currently authenticated user to memory.
     *
     * @return void
     */
    protected function loadCurrentUserSettings(User $user): void
    {
        $settings = new UserSettingsRepository(
            $this->app->make(SettingsService::class)->of($user)->get()
        );

        $this->app->instance('user.settings', $settings);
        $this->app->instance(UserSettings::class, $settings);
    }

    /**
     * Save the configurations for the currently authenticated user's organisaiton to memory.
     *
     * @param \Ignite\Interfaces\Users\User $user
     *
     * @return void
     */
    protected function loadCurrentOrganisationSettings(User $user): void
    {
        $settings = new OrganisationSettingsRepository(
            $this->app
                ->make(SettingsService::class)
                ->of($user->getOrganisation())
                ->get()
        );

        $this->app->instance('organisation.settings', $settings);
        $this->app->instance(OrganisationSettings::class, $settings);
    }
}
