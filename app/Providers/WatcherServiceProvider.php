<?php

namespace App\Providers;

use App\Interfaces\Watchers\WatcherEntry as WatcherEntryInterface;
use App\Interfaces\Watchers\WatcherManager as WatcherManagerInterface;
use App\Models\WatcherEntry;
use App\Watchers\WatcherManager;
use Illuminate\Support\ServiceProvider;

class WatcherServiceProvider extends ServiceProvider
{
    /**
     * The application watchers.
     *
     * @var array
     */
    protected array $watchers = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerWatcherEntryModel();
        $this->registerWatcherManager();
        $this->registerAppTerminatingEvents();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->startWatching();
    }

    /**
     * Register the watcher entry model.
     *
     * @return void
     */
    public function registerWatcherEntryModel(): void
    {
        $this->app->bind(WatcherEntryInterface::class, WatcherEntry::class);
    }

    /**
     * Get the application watchers.
     *
     * @return array
     */
    public function getWatchers(): array
    {
        return array_merge($this->watchers, config('watchers.list'));
    }

    /**
     * Get the watcher manager instance.
     *
     * @return \App\Interfaces\Watchers\WatcherManager
     */
    public function watcher(): WatcherManagerInterface
    {
        return $this->app->make(WatcherManagerInterface::class);
    }

    /**
     * Start watching the application.
     *
     * @return void
     */
    protected function startWatching(): void
    {
        if (! config('watchers.enabled')) {
            return;
        }

        $this->watcher()->start();
    }

    /**
     * Register the application watchers.
     *
     * @return void
     */
    protected function registerWatcherManager(): void
    {
        $this->app->singleton(WatcherManagerInterface::class, function ($app) {
            return new WatcherManager($app, [
                'watchers' => $this->getWatchers(),
            ]);
        });
    }

    /**
     * Register the application watchers.
     *
     * @return void
     */
    protected function registerAppTerminatingEvents(): void
    {
        $this->app->terminating(function (): void {
            $this->watcher()->stop();
        });
    }
}
