<?php

namespace App\Watchers;

use App\Enums\Entry;
use App\Interfaces\Watchers\Watcher as WatcherInterface;
use App\Interfaces\Watchers\WatcherManager;
use App\Support\InteractsWithContainer;
use App\ValueObjects\RuntimeKey;

abstract class Watcher implements WatcherInterface
{
    use InteractsWithContainer;

    /**
     * Create a new Watcher instance.
     *
     * @param \App\Interfaces\Watchers\WatcherManager $manager
     * @param \App\ValueObjects\RuntimeKey            $runtimeKey
     * @param array                                   $options
     *
     * @return void
     */
    public function __construct(
        protected readonly WatcherManager $manager,
        protected RuntimeKey $runtimeKey,
        protected ?array $options = []
    ) {
    }

    /**
     * Record an entry.
     *
     * @param \App\Enums\Entry            $type
     * @param \App\Watchers\IncomingEntry $entry
     *
     * @return void
     */
    public function record(Entry $type, IncomingEntry $entry): void
    {
        $this->getManager()->record($type, $entry);
    }

    /**
     * Get the watcher manager instance.
     *
     * @return \App\Interfaces\Watchers\WatcherManager
     */
    public function getManager(): WatcherManager
    {
        return $this->manager;
    }

    /**
     * Get the watcher options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * Determine if the watcher is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->getOptions()['enabled'] ?? false;
    }
}
