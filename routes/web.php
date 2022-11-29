<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Defibs\EditDefibController;
use App\Http\Controllers\Defibs\ViewDefibController;
use App\Http\Controllers\Defibs\ListDefibsController;
use App\Http\Controllers\Defibs\StoreDefibController;
use App\Http\Controllers\Defibs\CreateDefibController;
use App\Http\Controllers\Defibs\UpdateDefibController;
use App\Http\Controllers\Auth\AuthenticateUserController;

Route::get('/', IndexController::class)->name('index');

Route::middleware('guest')->group(function () {
    Route::get('login', LoginController::class)->name('login');
    Route::post('login', AuthenticateUserController::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::prefix('defibs')->name('defibs.')->group(function () {
        Route::get('/', ListDefibsController::class)->name('list')->can('defib.list');
        Route::post('/', StoreDefibController::class)->name('store')->can('defib.create');
        Route::get('/new', CreateDefibController::class)->name('create')->can('defib.create');
        Route::get('/{id}', ViewDefibController::class)->name('view')->can('defib.view');
        Route::get('/{id}/update', EditDefibController::class)->name('edit')->can('defib.update');
        Route::put('/{id}', UpdateDefibController::class)->name('update')->can('defib.update');
    });
});
