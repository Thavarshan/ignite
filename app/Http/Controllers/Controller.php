<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ReturnsResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ReturnsResponse;
}
