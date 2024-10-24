<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;

class AgentHandlerService
{
    /**
     * Get the next available agent.
     */
    public function getNextAvailableAgent(): ?User
    {
        return User::role(Role::AGENT->value)
            ->get()
            ->sortBy(function ($agent) {
                return $agent->assignedTickets()->count();
            })
            ->first();
    }
}
