<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        $isProduction = $this->app->isProduction();

        if (! $isProduction) {
            Model::preventLazyLoading();
            Model::preventSilentlyDiscardingAttributes();

            Model::retrieved(function (Model $model): void {
                $model->preventsLazyLoading = true;
            });
        }

        if ($isProduction) {
            DB::prohibitDestructiveCommands();
            URL::forceScheme('https');
        }
    }
}
