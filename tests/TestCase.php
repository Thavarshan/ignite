<?php

namespace Tests;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates a new user with optional role for testing.
     */
    protected function createUser(?Role $role = null): User
    {
        $user = User::factory()->create();

        if ($role) {
            $user->assignRole($role->value);
        }

        return $user;
    }
}
