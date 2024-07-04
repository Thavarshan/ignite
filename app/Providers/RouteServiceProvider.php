<?php

namespace App\Providers;

use App\Enums\AppMode;
use App\Facades\App;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters,
     * and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->getId() ?? $request->ip()
            );
        });

        $this->routes(function (): void {
            if (App::hasModeEnabled(AppMode::API)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group(static function (): void {
                        Route::prefix('v1')
                            ->as('v1:')
                            ->group(base_path('routes/api/v1/routes.php'));
                    });
            }

            if (App::hasModeEnabled(AppMode::WEB)) {
                Route::middleware('web')
                    ->group(base_path('routes/web/routes.php'));
            }
        });
    }
}
