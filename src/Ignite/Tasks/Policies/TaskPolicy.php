<?php

namespace Ignite\Tasks\Policies;

use Ignite\Auth\Policies\Policy;
use Ignite\Interfaces\Tasks\Taskable;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Tasks\Models\Task;
use Ignite\Users\Models\User;

class TaskPolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param \Ignite\Users\Models\User              $user
     * @param \Ignite\Interfaces\Tasks\Taskable|null $taskable
     *
     * @return bool
     */
    public function viewAny(User $user, ?Taskable $taskable = null): bool
    {
        $basicPermission = $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::TASKS)
        );

        if (! is_null($taskable)) {
            return $basicPermission && $user->belongsToOrganisation(
                $taskable->getOrganisation()
            );
        }

        return $basicPermission;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::TASKS)
        ) && $user->hasTask($task);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \Ignite\Users\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::CREATE, on: Resource::TASKS)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Tasks\Models\Task $task
     *
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::TASKS)
        ) && $user->hasTask($task);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Tasks\Models\Task $task
     *
     * @return bool
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::DELETE, on: Resource::TASKS)
        ) && $user->hasTask($task);
    }
}
