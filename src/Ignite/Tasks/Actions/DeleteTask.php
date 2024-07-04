<?php

namespace Ignite\Tasks\Actions;

use Ignite\Interfaces\Tasks\DeletesTasks;
use Ignite\Interfaces\Tasks\Task;

class DeleteTask implements DeletesTasks
{
    /**
     * Delete a task.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     * @param array<string, mixed>|null     $options
     *
     * @return void
     */
    public function delete(Task $task, ?array $options = []): void
    {
    }
}
