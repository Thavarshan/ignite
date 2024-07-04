<?php

namespace Ignite\Tasks\Actions;

use Ignite\Interfaces\Tasks\CreatesNewTasks;
use Ignite\Interfaces\Tasks\Task;
use Ignite\Interfaces\Tasks\TaskService;

class CreateNewTask implements CreatesNewTasks
{
    /**
     * Create a new task action instance.
     *
     * @param \Ignite\Interfaces\Tasks\TaskService $service
     *
     * @return void
     */
    public function __construct(protected TaskService $service)
    {
    }

    /**
     * Create a task.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return \Ignite\Interfaces\Tasks\Task
     */
    public function create(array $data, ?array $options = []): Task
    {
        return $this->service->create($data);
    }
}
