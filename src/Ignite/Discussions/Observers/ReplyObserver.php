<?php

namespace Ignite\Discussions\Observers;

use Ignite\Discussions\Models\Reply;

class ReplyObserver
{
    /**
     * Handle the Reply "created" event.
     *
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return void
     */
    public function created(Reply $reply): void
    {
        //
    }

    /**
     * Handle the Reply "updated" event.
     *
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return void
     */
    public function updated(Reply $reply): void
    {
        //
    }

    /**
     * Handle the Reply "deleted" event.
     *
     * @param \Ignite\Discussions\Models\Reply $reply
     *
     * @return void
     */
    public function deleted(Reply $reply): void
    {
        //
    }
}
