<?php

namespace Ignite\Notes\Policies;

use Ignite\Auth\Policies\Policy;
use Ignite\Notes\Models\Note;
use Ignite\Shared\Enums\Action;
use Ignite\Shared\Enums\Resource;
use Ignite\Users\Models\User;

class NotePolicy extends Policy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return bool
     */
    public function view(User $user, Note $note): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::READ, on: Resource::NOTES)
        );
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
            $this->do(Action::CREATE, on: Resource::NOTES)
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return bool
     */
    public function update(User $user, Note $note): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::UPDATE, on: Resource::NOTES)
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Ignite\Users\Models\User $user
     * @param \Ignite\Notes\Models\Note $note
     *
     * @return bool
     */
    public function delete(User $user, Note $note): bool
    {
        return $user->hasPermissionTo(
            $this->do(Action::DELETE, on: Resource::NOTES)
        );
    }
}
