<?php

namespace App\Interfaces\Watchers;

use App\Enums\Entry;
use App\Watchers\IncomingEntry;
use Closure;

interface WatcherManager
{
    /**
     * Start the recording process.
     *
     * @return void
     */
    public function start(): void;

    /**
     * Register all of the recordable watchers.
     *
     * @return void
     */
    public function registerWatchers(): void;

    /**
     * Get all recordable watchers.
     *
     * @return array
     */
    public function getWatchers(): array;

    /**
     * Record the given entry.
     *
     * @param \App\Enums\Entry            $entry
     * @param \App\Watchers\IncomingEntry $entry
     *
     * @return void
     */
    public function record(Entry $type, IncomingEntry $entry): void;

    /**
     * Execute the given callback without recording Telescope entries.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function withoutRecording(Closure $callback): void;

    /**
     * Determine if the watcher is recording.
     *
     * @return bool
     */
    public function isRecording(): bool;

    /**
     * Get all of the registered watchers.
     *
     * @return array
     */
    public function getRegisteredWatchers(): array;

    /**
     * Stop the recording process.
     *
     * @return void
     */
    public function stop(): void;

    /**
     * Get the entries queue.
     *
     * @return array
     */
    public function getEntriesQueue(): array;

    /**
     * Flush the entries queue.
     *
     * @return void
     */
    public function flushEntriesQueue(): void;

    /**
     * Set a list of watchers to watch application actions.
     *
     * @param array $watchers
     *
     * @return self
     */
    public function setWatchers(array $watchers): self;
}
