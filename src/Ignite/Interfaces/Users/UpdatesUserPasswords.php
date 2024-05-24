<?php

namespace Ignite\Interfaces\Users;

use Laravel\Fortify\Contracts\UpdatesUserPasswords as FortifyUpdatesUserPasswords;

interface UpdatesUserPasswords extends FortifyUpdatesUserPasswords
{
    /**
     * Validate and update the user's password.
     *
     * @param array<string, mixed>      $data
     * @param array<string, mixed>|null $options
     *
     * @return void
     */
    public function update(User $user, array $data, ?array $options = []): void;
}
