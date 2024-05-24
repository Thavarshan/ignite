<?php

use Ignite\Auth\Http\AuthenticationControllers;
use Ignite\Auth\Http\RegistrationControllers;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticationControllers::class, 'create'])->name('login');
Route::post('/login', [AuthenticationControllers::class, 'store']);

Route::get('/register', [RegistrationControllers::class, 'create'])->name('register');
Route::post('/register', [RegistrationControllers::class, 'store']);
