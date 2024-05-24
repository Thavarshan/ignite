<?php

namespace Ignite\Users\Actions;

use Ignite\Interfaces\Auth\ResetsUserPasswords;
use Ignite\Interfaces\Users\UpdatesUserPasswords;
use Ignite\Interfaces\Users\User;

class UpdateUserPassword implements UpdatesUserPasswords
{
    /**
     * Create a new action instance.
     *
     * @param \Ignite\Interfaces\Auth\ResetsUserPasswords $reseter
     *
     * @return void
     */
    public function __construct(protected readonly ResetsUserPasswords $reseter)
    {
    }

    /**
     * Validate and update the user's password.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return void
     */
    public function update(User $user, array $data, ?array $options = []): void
    {
        $this->reseter->reset($user, $data, $options);
    }
}
