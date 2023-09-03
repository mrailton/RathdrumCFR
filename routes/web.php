<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalloutController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InviteUsersController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserRequestedReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersPermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/contact', [ContactFormController::class, 'show'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'process'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login.create');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    Route::get('/register/{invite:token}', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('auth.logout');

    Route::prefix('callouts')->name('callouts.')->group(function () {
        Route::get('/', [CalloutController::class, 'list'])->name('list')->can('callout.list');
        Route::post('/', [CalloutController::class, 'store'])->name('store')->can('callout.create');
        Route::get('/new', [CalloutController::class, 'create'])->name('create')->can('callout.create');
        Route::get('/{callout}', [CalloutController::class, 'show'])->name('show')->can('callout.view');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'list'])->name('list')->can('user.list');
        Route::get('/invite', [InviteUsersController::class, 'create'])->name('invite.create')->can('user.invite');
        Route::post('/invite', [InviteUsersController::class, 'store'])->name('invite.store')->can('user.invite');
        Route::get('/{user}', [UsersController::class, 'show'])->name('show')->can('user.view');
        Route::get('/{user}/reports', [UserRequestedReportsController::class, 'show'])->name('reports.show')->can('user.update');
        Route::put('/{user}/reports', [UserRequestedReportsController::class, 'store'])->name('reports.store')->can('user.update');
        Route::get('/{user}/permissions', [UsersPermissionsController::class, 'show'])->name('permissions.show')->can('user.permissions');
        Route::put('/{user}/permissions', [UsersPermissionsController::class, 'update'])->name('permissions.update')->can('user.permissions');
    });
});
