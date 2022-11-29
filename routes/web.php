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
use App\Http\Controllers\Defibs\Notes\StoreDefibNoteController;
use App\Http\Controllers\Defibs\Notes\CreateDefibNoteController;
use App\Http\Controllers\Defibs\Inspections\StoreDefibInspectionController;
use App\Http\Controllers\Defibs\Inspections\CreateDefibInspectionController;

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

        Route::prefix('/{id}/inspections')->name('inspections.')->group(function () {
            Route::get('/new', CreateDefibInspectionController::class)->name('create')->can('defib.inspect');
            Route::post('/', StoreDefibInspectionController::class)->name('store')->can('defib.inspect');
        });

        Route::prefix('/{id}/notes')->name('notes.')->group(function () {
            Route::get('/new', CreateDefibNoteController::class)->name('create')->can('defib.note');
            Route::post('/', StoreDefibNoteController::class)->name('store')->can('defib.note');
        });
    });
});
