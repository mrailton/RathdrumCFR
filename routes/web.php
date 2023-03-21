<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\StoreRegistrationController;
use App\Http\Controllers\CalloutController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\DefibController;
use App\Http\Controllers\DefibInspectionController;
use App\Http\Controllers\DefibNoteController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InviteUsersController;
use App\Http\Controllers\Members\CreateMemberController;
use App\Http\Controllers\Members\EditMemberController;
use App\Http\Controllers\Members\ListMembersController;
use App\Http\Controllers\Members\Notes\CreateMemberNoteController;
use App\Http\Controllers\Members\Notes\StoreMemberNoteController;
use App\Http\Controllers\Members\StoreMemberController;
use App\Http\Controllers\Members\UpdateMemberController;
use App\Http\Controllers\Members\ViewMemberController;
use App\Http\Controllers\UserRequestedReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersPermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/contact', [ContactFormController::class, 'show'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'process'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginController::class)->name('login.create');
    Route::post('/login', AuthenticateUserController::class)->name('login.store');
    Route::get('/register/{invite:token}', RegistrationController::class)->name('register.create');
    Route::post('/register', StoreRegistrationController::class)->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', LogoutController::class)->name('auth.logout');

    Route::prefix('defibs')->name('defibs.')->group(function () {
        Route::get('/', [DefibController::class, 'list'])->name('list')->can('defib.list');
        Route::post('/', [DefibController::class, 'store'])->name('store')->can('defib.create');
        Route::get('/new', [DefibController::class, 'create'])->name('create')->can('defib.create');
        Route::get('/{defib}', [DefibController::class, 'show'])->name('view')->can('defib.view');
        Route::get('/{defib}/update', [DefibController::class, 'edit'])->name('edit')->can('defib.update');
        Route::put('/{defib}', [DefibController::class, 'update'])->name('update')->can('defib.update');

        Route::prefix('/{defib}/inspections')->name('inspections.')->group(function () {
            Route::get('/new', [DefibInspectionController::class, 'create'])->name('create')->can('defib.inspect');
            Route::post('/', [DefibInspectionController::class, 'store'])->name('store')->can('defib.inspect');
        });

        Route::prefix('/{defib}/notes')->name('notes.')->group(function () {
            Route::get('/new', [DefibNoteController::class, 'create'])->name('create')->can('defib.note');
            Route::post('/', [DefibNoteController::class, 'store'])->name('store')->can('defib.note');
        });
    });

    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', ListMembersController::class)->name('list')->can('member.list');
        Route::post('/', StoreMemberController::class)->name('store')->can('member.create');
        Route::get('/new', CreateMemberController::class)->name('create')->can('member.create');
        Route::get('/{member}', ViewMemberController::class)->name('view')->can('member.view');
        Route::get('/{member}/update', EditMemberController::class)->name('edit')->can('member.update');
        Route::put('/{member}', UpdateMemberController::class)->name('update')->can('member.update');

        Route::prefix('/{member}/notes')->name('notes.')->group(function () {
            Route::get('/new', CreateMemberNoteController::class)->name('create')->can('member.note');
            Route::post('/', StoreMemberNoteController::class)->name('store')->can('member.note');
        });
    });

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
