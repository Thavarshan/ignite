<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\JetstreamServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    JetstreamServiceProvider::class,
    PermissionServiceProvider::class,
];
