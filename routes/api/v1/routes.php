<?php

use App\Http\Controllers\Api\ChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
], function (): void {
    Route::apiResource('channels', ChannelController::class)->except(['show']);
});
