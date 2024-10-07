<?php

use App\Enums\Role;
use App\Enums\Resource;
use App\Enums\Permission;

return [
    Role::ADMIN->value => [
        Resource::USER->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::ROLE->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::PERMISSION->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::CHANNEL->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::THREAD->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::NOTE->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::REPLY->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
    ],

    Role::AGENT->value => [
        Resource::USER->value => [
            Permission::READ->value,
        ],
        Resource::ROLE->value => [
            Permission::READ->value,
        ],
        Resource::PERMISSION->value => [
            Permission::READ->value,
        ],
        Resource::CHANNEL->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::THREAD->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::NOTE->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::REPLY->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
    ],

    Role::USER->value => [
        Resource::USER->value => [
            Permission::READ->value,
            Permission::UPDATE->value,
        ],
        Resource::CHANNEL->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::THREAD->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::NOTE->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
        Resource::REPLY->value => [
            Permission::CREATE->value,
            Permission::READ->value,
            Permission::UPDATE->value,
            Permission::DELETE->value,
        ],
    ],

    Role::GUEST->value => [
        Resource::CHANNEL->value => [
            Permission::READ->value,
        ],
        Resource::THREAD->value => [
            Permission::READ->value,
        ],
        Resource::NOTE->value => [
            Permission::READ->value,
        ],
        Resource::REPLY->value => [
            Permission::READ->value,
        ],
    ],
];
