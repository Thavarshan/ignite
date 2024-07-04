<?php

namespace Ignite\Tasks\Models\Traits;

use Ignite\Interfaces\Tasks\Task as TaskInterface;
use Ignite\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTasks
{
    /**
     * Get all tasks assigned to this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks(): BelongsToMany
    {
        $relations = $this->belongsToMany(Task::class);

        if (property_exists($this, 'tasksPivotModel')) {
            $relations->using($this->tasksPivotModel);
        }

        return $relations;
    }

    /**
     * Assign the given task to this model.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     */
    public function assignTask(TaskInterface $task): void
    {
        $this->tasks()->syncWithoutDetaching($task);
    }

    /**
     * Remove the given task from this model.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     */
    public function removeTask(TaskInterface $task): void
    {
        $this->tasks()->detach($task);
    }

    /**
     * Determine if the given task is assigned to this model.
     *
     * @param \Ignite\Interfaces\Tasks\Task $task
     *
     * @return bool
     */
    public function hasTask(TaskInterface $task): bool
    {
        return $this->tasks->contains($task);
    }
}
