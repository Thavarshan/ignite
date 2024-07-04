<?php

namespace Ignite\Search\Providers;

use Ignite\Interfaces\Search\Engine;
use Ignite\Interfaces\Search\EngineManager as EngineManagerInterface;
use Ignite\Interfaces\Search\Manifest as ManifestInterface;
use Ignite\Interfaces\Search\PerformsSearches;
use Ignite\Search\Actions\PerformSearch;
use Ignite\Search\Engines\EngineManager;
use Ignite\Search\Engines\Manifest;
use Ignite\Search\Engines\ScoutEngine;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
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
        $this->registerEngine();
        $this->registerManifest();
        $this->registerManager();

        $this->registerActions();
    }

    /**
     * Register the search engine.
     *
     * @return void
     */
    protected function registerEngine(): void
    {
        $this->app->singleton(Engine::class, function ($app) {
            return $app->make(ScoutEngine::class);
        });
    }

    /**
     * Register the search manifest.
     *
     * @return void
     */
    protected function registerManifest(): void
    {
        $this->app->singleton(ManifestInterface::class, function ($app) {
            $manifest = new Manifest();

            $manifest->setModels($app['config']->get('search.models'));

            return $manifest;
        });
    }

    /**
     * Register the search manager.
     *
     * @return void
     */
    protected function registerManager(): void
    {
        $this->app->singleton(EngineManagerInterface::class, function ($app) {
            return new EngineManager(
                $app->make(Engine::class),
                $app->make(ManifestInterface::class)
            );
        });
    }

    /**
     * Register the search actions.
     *
     * @return void
     */
    protected function registerActions(): void
    {
        $this->app->singleton(PerformsSearches::class, PerformSearch::class);
    }
}
