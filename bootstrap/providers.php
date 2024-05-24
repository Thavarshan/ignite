<?php

use App\Providers\AppServiceProvider;
use App\Providers\ClientServiceProvider;
use App\Providers\ViewServiceProvider;
use Ignite\Auth\Providers\AuthServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    ClientServiceProvider::class,
    ViewServiceProvider::class,
];
