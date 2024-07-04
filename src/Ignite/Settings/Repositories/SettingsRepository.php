<?php

namespace Ignite\Settings\Repositories;

use Ignite\Interfaces\Settings\Settings;
use Ignite\Interfaces\Settings\SettingsRepository as SettingsRepositoryInterface;
use Ignite\Shared\Data\Repositories\Repository;

class SettingsRepository extends Repository implements SettingsRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Ignite\Interfaces\Settings\Settings $user
     *
     * @return void
     */
    public function __construct(Settings $user)
    {
        $this->model = $user;
    }
}
