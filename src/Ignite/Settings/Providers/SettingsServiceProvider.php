<?php

namespace Ignite\Settings\Providers;

use Ignite\Interfaces\Settings\Settings as SettingsInterface;
use Ignite\Interfaces\Settings\SettingsFilter as SettingsFilterInterface;
use Ignite\Interfaces\Settings\SettingsRepository as SettingsRepositoryInterface;
use Ignite\Interfaces\Settings\SettingsService as SettingsServiceInterface;
use Ignite\Interfaces\Settings\UpdatesEntitySettings;
use Ignite\Settings\Actions\UpdateEntitySettings;
use Ignite\Settings\Filters\SettingsFilter;
use Ignite\Settings\Models\Settings;
use Ignite\Settings\Repositories\SettingsRepository;
use Ignite\Settings\Services\SettingsService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
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
        $this->registerActions();

        $this->registerResolutionLogic();
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerFilters(): void
    {
        $this->app->singleton(
            SettingsFilterInterface::class,
            SettingsFilter::class
        );
    }

    /**
     * Register all repository classes.
     *
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(
            SettingsRepositoryInterface::class,
            SettingsRepository::class
        );
    }

    /**
     * Register all service classes.
     *
     * @return void
     */
    public function registerServices(): void
    {
        $this->app->singleton(SettingsServiceInterface::class, function ($app) {
            return new SettingsService($app->make(SettingsRepositoryInterface::class));
        });
    }

    /**
     * Register all model classes.
     *
     * @return void
     */
    protected function registerModels(): void
    {
        $this->app->singleton(
            SettingsInterface::class,
            fn ($app) => $app->make(Settings::class)
        );
    }

    /**
     * Register all action classes.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(
            UpdatesEntitySettings::class,
            UpdateEntitySettings::class
        );
    }

    /**
     * Register resource resolution logic.
     *
     * @return void
     */
    protected function registerResolutionLogic(): void
    {
        Route::bind('settings', function (string $value) {
            return $this->app->make(
                SettingsServiceInterface::class
            )->find($value);
        });
    }
}
