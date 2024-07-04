<?php

use App\Watchers\EventWatcher;

return [
    'enabled' => env('APP_WATCH', false),

    'list' => [
        EventWatcher::class => [
            'enabled' => true,
        ],
    ],
];
