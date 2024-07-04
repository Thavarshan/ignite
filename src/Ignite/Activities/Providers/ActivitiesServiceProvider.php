<?php

namespace Ignite\Activities\Providers;

use Ignite\Activities\Filters\ActivityFilter;
use Ignite\Activities\Http\Responses\ActivityCollectionResponse;
use Ignite\Activities\Models\Activity;
use Ignite\Activities\Repositories\ActivityRepository;
use Ignite\Activities\Services\ActivityService;
use Ignite\Interfaces\Activities\Activity as ActivityInterface;
use Ignite\Interfaces\Activities\ActivityCollectionResponse as ActivityCollectionResponseInterface;
use Ignite\Interfaces\Activities\ActivityFilter as ActivityFilterInterface;
use Ignite\Interfaces\Activities\ActivityRepository as ActivityRepositoryInterface;
use Ignite\Interfaces\Activities\ActivityService as ActivityServiceInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ActivitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerModels();
        $this->registerFilters();
        $this->registerRepositories();
        $this->registerServices();
        $this->registerResponses();

        $this->registerResolutionLogic();
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerFilters(): void
    {
        $this->app->singleton(ActivityFilterInterface::class, ActivityFilter::class);
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(
            ActivityRepositoryInterface::class,
            ActivityRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(ActivityServiceInterface::class, function ($app) {
            return new ActivityService($app->make(ActivityRepositoryInterface::class));
        });
    }

    /**
     * Register all response classes.
     *
     * @return void
     */
    public function registerResponses(): void
    {
        $this->app->bind(
            ActivityCollectionResponseInterface::class,
            ActivityCollectionResponse::class
        );
    }

    /**
     * Register all model classes.
     *
     * @return void
     */
    protected function registerModels(): void
    {
        $this->app->singleton(
            ActivityInterface::class,
            fn ($app) => $app->make(Activity::class)
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('activity', function (string $value) {
            return $this->app->make(
                ActivityServiceInterface::class
            )->find($value);
        });
    }
}
