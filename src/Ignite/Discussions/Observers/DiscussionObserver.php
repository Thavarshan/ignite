<?php

namespace Ignite\Discussions\Observers;

use Ignite\Discussions\Models\Discussion;

class DiscussionObserver
{
    /**
     * Handle the Discussion "created" event.
     *
     * @param \Ignite\Discussions\Models\Discussion $discussion
     *
     * @return void
     */
    public function created(Discussion $discussion): void
    {
        //
    }

    /**
     * Handle the Discussion "updated" event.
     *
     * @param \Ignite\Discussions\Models\Discussion $discussion
     *
     * @return void
     */
    public function updated(Discussion $discussion): void
    {
        //
    }

    /**
     * Handle the Discussion "deleted" event.
     *
     * @param \Ignite\Discussions\Models\Discussion $discussion
     *
     * @return void
     */
    public function deleted(Discussion $discussion): void
    {
        //
    }
}
