<?php

use Illuminate\Support\Facades\Route;
use KieranFYI\Admin\Facades\Admin;
use KieranFYI\UserUI\Http\Controllers\UserAPIController;
use KieranFYI\UserUI\Http\Controllers\UserController;

Admin::route()
    ->group(function () {

        Route::resource('users', UserController::class)
            ->only('index', 'create', 'show');

        Route::prefix('api')
            ->name('api.')
            ->group(function () {
                Route::get('users/{user}/logs', [UserAPIController::class, 'logs'])
                    ->name('users.logs');
                Route::post('users/{user}/logs', [UserAPIController::class, 'logs'])
                    ->name('users.logs');

                Route::resource('users', UserAPIController::class)
                    ->except('create', 'edit');
                Route::post('users/search', [UserAPIController::class, 'search'])
                    ->name('users.search');
            });
    });