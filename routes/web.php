<?php

use Illuminate\Support\Facades\Route;
use KieranFYI\Admin\Facades\Admin;
use KieranFYI\UserUI\Http\Controllers\UserController;

Admin::route()
    ->group(function () {
        Route::resource('users', UserController::class)
            ->except('edit');
    });