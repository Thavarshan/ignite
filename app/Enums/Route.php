<?php

namespace App\Enums;

use App\Providers\RouteServiceProvider;

enum Route: string
{
    case HOME = RouteServiceProvider::HOME;
}
