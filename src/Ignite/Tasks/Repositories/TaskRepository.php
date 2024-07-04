<?php

namespace Ignite\Tasks\Repositories;

use Ignite\Interfaces\Tasks\Task;
use Ignite\Interfaces\Tasks\TaskRepository as TaskRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class TaskRepository extends Repository implements TaskRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->model = $task;
    }
}
