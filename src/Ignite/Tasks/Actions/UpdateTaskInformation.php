<?php

namespace Ignite\Tasks\Actions;

use Ignite\Interfaces\Tasks\Task;
use Ignite\Interfaces\Tasks\UpdatesTaskInformation;

class UpdateTaskInformation implements UpdatesTaskInformation
{
    /**
     * Update a task.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     * @param array<string, mixed>          $data
     * @param array<string, mixed>|null     $options
     *
     * @return \Ignite\Interfaces\Tasks\Task
     */
    public function update(Task $task, array $data, ?array $options = []): Task
    {
    }
}
