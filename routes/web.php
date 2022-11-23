<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Defibs\ListDefibsController;
use App\Http\Controllers\Auth\AuthenticateUserController;

Route::get('/', IndexController::class)->name('index');

Route::middleware('guest')->group(function () {
    Route::get('login', LoginController::class)->name('login');
    Route::post('login', AuthenticateUserController::class)->name('login');
});

Route::middleware('auth')->name('defibs.')->prefix('defibs')->group(function () {
    Route::get('/', ListDefibsController::class)->name('list');
});
