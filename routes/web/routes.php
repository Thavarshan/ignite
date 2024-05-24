<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Authentication routes...
 */
require route_path('common/auth.php');

Route::get('/', static function () {
    return Inertia::render('landing/index');
});
