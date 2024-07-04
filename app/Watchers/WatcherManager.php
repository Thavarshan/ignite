<?php

namespace App\Watchers;

use App\Enums\Entry as EntryType;
use App\Interfaces\Watchers\WatcherEntry;
use App\Interfaces\Watchers\WatcherManager as WatcherManagerInterface;
use App\Support\Loggable;
use Closure;
use Ignite\Auth\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

class WatcherManager implements WatcherManagerInterface
{
    use Loggable;

    /**
     * Indicates if the watcher is recording.
     *
     * @var bool
     */
    protected bool $recording = false;

    /**
     * All of the registered watchers.
     *
     * @var array
     */
    protected array $registeredWatchers = [];

    /**
     * The entries queue.
     *
     * @var array
     */
    protected array $entriesQueue = [];

    /**
     * The chunk size for storing entries.
     *
     * @var int
     */
    protected int $chunkSize = 1000;

    /**
     * Create a new WatcherManager instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array                                        $options
     *
     * @return void
     */
    public function __construct(
        protected readonly Application $app,
        protected array $options = []
    ) {
    }

    /**
     * Start the recording process.
     *
     * @return void
     */
    public function start(): void
    {
        $this->registerWatchers();

        $this->recording = $this->handlingApprovedRequest();
    }

    /**
     * Register all of the recordable watchers.
     *
     * @return void
     */
    public function registerWatchers(): void
    {
        collect($this->getWatchers())
            ->filter(function ($options, $watcher) {
                return Arr::get($options, 'enabled', false);
            })
            ->each(function ($options, $watcher) {
                $watcher = $this->app->make($watcher, [
                    'manager' => $this,
                    'options' => $options,
                ]);

                $watcher->register($this->app);

                $this->registeredWatchers[$watcher::class] = $watcher;
            });
    }

    /**
     * Get all recordable watchers.
     *
     * @return array
     */
    public function getWatchers(): array
    {
        return array_filter(
            Arr::get($this->options, 'watchers', [])
        );
    }

    /**
     * Determine if the watcher is handling an approved request.
     *
     * @return bool
     */
    public function handlingApprovedRequest(): bool
    {
        if ($this->app->runningUnitTests()) {
            return true;
        }

        return ! $this->app->runningInConsole() && $this->app->isProduction();
    }

    /**
     * Record the given entry.
     *
     * @param \App\Enums\Entry            $type
     * @param \App\Watchers\IncomingEntry $entry
     *
     * @return void
     */
    public function record(EntryType $type, IncomingEntry $entry): void
    {
        if (! $this->isRecording()) {
            return;
        }

        $entry->setType($type);

        try {
            if (Auth::hasResolvedGuards() && Auth::hasUser()) {
                $entry->setUser(Auth::user());
            }
        } catch (Throwable $e) {
            $this->logThrowable($e);
        }

        $this->withoutRecording(function () use ($entry) {
            $this->entriesQueue[] = $entry;
        });
    }

    /**
     * Execute the given callback without recording Telescope entries.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function withoutRecording(Closure $callback): void
    {
        $shouldRecord = $this->recording;

        $this->recording = false;

        try {
            // Section prone to exceptions and since no
            // catch is specified segfaults will occur.
            call_user_func($callback);
        } finally {
            $this->recording = $shouldRecord;
        }
    }

    /**
     * Determine if the watcher is recording.
     *
     * @return bool
     */
    public function isRecording(): bool
    {
        return $this->recording;
    }

    /**
     * Get all of the registered watchers.
     *
     * @return array
     */
    public function getRegisteredWatchers(): array
    {
        return $this->registeredWatchers;
    }

    /**
     * Stop the recording process.
     *
     * @return void
     */
    public function stop(): void
    {
        if (! $this->isRecording()) {
            return;
        }

        $this->recording = false;

        try {
            $this->storeEntires();
        } catch (Throwable $e) {
            $this->logThrowable($e);
        } finally {
            $this->flushEntriesQueue();
        }
    }

    /**
     * Store the entries.
     *
     * @return void
     */
    public function storeEntires(): void
    {
        $this->withoutRecording(function () {
            $batchId = Str::orderedUuid()->toString();

            collect($this->getEntriesQueue())
                ->chunk($this->chunkSize)
                ->each(function ($entries) use ($batchId) {
                    $entries->each(function (IncomingEntry $entry) use ($batchId) {
                        $entry->setBatchId($batchId);

                        tap($this->app->make(
                            WatcherEntry::class
                        ), function ($model) use ($entry) {
                            $model->store($entry->toArray());
                        });
                    });
                });
        });
    }

    /**
     * Get the entries queue.
     *
     * @return array
     */
    public function getEntriesQueue(): array
    {
        return $this->entriesQueue;
    }

    /**
     * Flush the entries queue.
     *
     * @return void
     */
    public function flushEntriesQueue(): void
    {
        $this->entriesQueue = [];
    }

    /**
     * Set a list of watchers to watch application actions.
     *
     * @param array $watchers
     *
     * @return self
     */
    public function setWatchers(array $watchers): self
    {
        $this->options['watchers'] = array_merge(
            $this->options['watchers'] ?? [],
            $watchers
        );

        return $this;
    }

    /**
     * Get the application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application|null
     */
    public function getAppInstance(): ?Application
    {
        return $this->app;
    }
}
