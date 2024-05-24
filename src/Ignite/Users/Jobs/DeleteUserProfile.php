<?php

namespace Ignite\Users\Jobs;

use Ignite\Interfaces\Users\DeletesUsers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class DeleteUserProfile extends UserJob implements ShouldQueue
{
    /**
     * Execute the job.
     *
     * @param \Ignite\Interfaces\Users\DeletesUsers $deleter
     *
     * @return void
     */
    public function handle(DeletesUsers $deleter): void
    {
        $deleter->delete($this->getUser());
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        $this->logError($exception->getMessage(), ['user' => $this->getUser()]);
    }
}
