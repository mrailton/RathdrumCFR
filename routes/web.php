<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Contact\ContactUsPageController;
use App\Http\Controllers\Contact\ProcessContactUsController;
use App\Http\Controllers\Defibs\CreateDefibController;
use App\Http\Controllers\Defibs\EditDefibController;
use App\Http\Controllers\Defibs\Inspections\CreateDefibInspectionController;
use App\Http\Controllers\Defibs\Inspections\StoreDefibInspectionController;
use App\Http\Controllers\Defibs\ListDefibsController;
use App\Http\Controllers\Defibs\Notes\CreateDefibNoteController;
use App\Http\Controllers\Defibs\Notes\StoreDefibNoteController;
use App\Http\Controllers\Defibs\StoreDefibController;
use App\Http\Controllers\Defibs\UpdateDefibController;
use App\Http\Controllers\Defibs\ViewDefibController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Members\CreateMemberController;
use App\Http\Controllers\Members\ListMembersController;
use App\Http\Controllers\Members\Notes\CreateMemberNoteController;
use App\Http\Controllers\Members\Notes\StoreMemberNoteController;
use App\Http\Controllers\Members\StoreMemberController;
use App\Http\Controllers\Members\ViewMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/contact', ContactUsPageController::class)->name('contact');
Route::post('/contact', ProcessContactUsController::class)->name('contact');

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

    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', ListMembersController::class)->name('list')->can('member.list');
        Route::post('/', StoreMemberController::class)->name('store')->can('member.create');
        Route::get('/new', CreateMemberController::class)->name('create')->can('member.create');
        Route::get('/{id}', ViewMemberController::class)->name('view')->can('member.view');

        Route::prefix('/{id}/notes')->name('notes.')->group(function () {
            Route::get('/new', CreateMemberNoteController::class)->name('create')->can('member.note');
            Route::post('/', StoreMemberNoteController::class)->name('store')->can('member.note');
        });
    });
});
