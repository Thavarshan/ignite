<?php

namespace Ignite\Tasks\Models\Traits;

use Ignite\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Taskable
{
    /**
     * Get the tasks for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    /**
     * Add a task to the model.
     *
     * @param string $name
     * @param string $description
     *
     * @return \Ignite\Tasks\Models\Task
     */
    public function addTask(string $name, string $description): Task
    {
        return $this->tasks()->create([
            'name' => $name,
            'description' => $description,
            'organisation_id' => $this->getOrganisationId(),
        ]);
    }

    /**
     * Remove the given task from the model.
     *
     * @param \Ignite\Tasks\Models\Task $task
     *
     * @return void
     */
    public function removeTask(Task $task): void
    {
        $task->delete();
    }
}
