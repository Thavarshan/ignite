<?php

namespace App\Providers;

use App\Actions\CreateThread;
use App\Contracts\CreatesThreads;
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
        $this->configureJsonResources();
        $this->configureModels();
        $this->configureProductionEnvironment();

        $this->registerActions();
    }

    /**
     * Configure JSON resource behavior.
     */
    protected function configureJsonResources(): void
    {
        JsonResource::withoutWrapping();
    }

    /**
     * Configure Eloquent model behavior.
     */
    protected function configureModels(): void
    {
        if ($this->app->isProduction()) {
            return;
        }

        Model::preventLazyLoading();
        Model::preventSilentlyDiscardingAttributes();

        Model::retrieved(function (Model $model): void {
            $model->preventsLazyLoading = true;
        });
    }

    /**
     * Configure production-specific settings.
     */
    protected function configureProductionEnvironment(): void
    {
        if (! $this->app->isProduction()) {
            return;
        }

        DB::prohibitDestructiveCommands();
        URL::forceScheme('https');
    }

    /**
     * Register action implementations.
     */
    protected function registerActions(): void
    {
        $this->app->bind(CreatesThreads::class, CreateThread::class);
    }
}
