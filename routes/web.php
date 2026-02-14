<?php

declare(strict_types=1);

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/contact', [ContactFormController::class, 'show'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'process'])->name('contact.store');

// Auth Routes
Route::get('/login', fn () => view('auth.login'))->name('login')->middleware('guest');

Route::post('/login', function () {
    request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt(request()->only('email', 'password'), request()->filled('remember'))) {
        request()->session()->regenerate();
        return redirect()->intended('/admin');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.store')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function (): void {
    // Dashboard
    Route::get('/', App\Http\Controllers\Admin\Dashboard\IndexDashboardController::class)->name('dashboard');

    // Members
    Route::prefix('members')->name('members.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\Members\IndexMembersController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Members\CreateMemberController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\Members\StoreMemberController::class)->name('store');
        Route::get('/{member}', App\Http\Controllers\Admin\Members\ShowMemberController::class)->name('show');
        Route::get('/{member}/edit', App\Http\Controllers\Admin\Members\EditMemberController::class)->name('edit');
        Route::put('/{member}', App\Http\Controllers\Admin\Members\UpdateMemberController::class)->name('update');
        Route::delete('/{member}', App\Http\Controllers\Admin\Members\DestroyMemberController::class)->name('destroy');
        Route::patch('/{member}/restore', App\Http\Controllers\Admin\Members\RestoreMemberController::class)->name('restore');
    });

    // Callouts
    Route::prefix('callouts')->name('callouts.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\Callouts\IndexCalloutsController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Callouts\CreateCalloutController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\Callouts\StoreCalloutController::class)->name('store');
        Route::get('/{callout}', App\Http\Controllers\Admin\Callouts\ShowCalloutController::class)->name('show');
        Route::get('/{callout}/edit', App\Http\Controllers\Admin\Callouts\EditCalloutController::class)->name('edit');
        Route::put('/{callout}', App\Http\Controllers\Admin\Callouts\UpdateCalloutController::class)->name('update');
        Route::delete('/{callout}', App\Http\Controllers\Admin\Callouts\DestroyCalloutController::class)->name('destroy');
    });

    // Defibs
    Route::prefix('defibs')->name('defibs.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\Defibs\IndexDefibsController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Defibs\CreateDefibController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\Defibs\StoreDefibController::class)->name('store');
        Route::get('/{defib}', App\Http\Controllers\Admin\Defibs\ShowDefibController::class)->name('show');
        Route::get('/{defib}/edit', App\Http\Controllers\Admin\Defibs\EditDefibController::class)->name('edit');
        Route::put('/{defib}', App\Http\Controllers\Admin\Defibs\UpdateDefibController::class)->name('update');
        Route::delete('/{defib}', App\Http\Controllers\Admin\Defibs\DestroyDefibController::class)->name('destroy');
        Route::patch('/{defib}/restore', App\Http\Controllers\Admin\Defibs\RestoreDefibController::class)->name('restore');
    });

    // Training Sessions
    Route::prefix('training-sessions')->name('training-sessions.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\TrainingSessions\IndexTrainingSessionsController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\TrainingSessions\CreateTrainingSessionController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\TrainingSessions\StoreTrainingSessionController::class)->name('store');
        Route::get('/{trainingSession}', App\Http\Controllers\Admin\TrainingSessions\ShowTrainingSessionController::class)->name('show');
        Route::get('/{trainingSession}/edit', App\Http\Controllers\Admin\TrainingSessions\EditTrainingSessionController::class)->name('edit');
        Route::put('/{trainingSession}', App\Http\Controllers\Admin\TrainingSessions\UpdateTrainingSessionController::class)->name('update');
        Route::delete('/{trainingSession}', App\Http\Controllers\Admin\TrainingSessions\DestroyTrainingSessionController::class)->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\Users\IndexUsersController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Users\CreateUserController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\Users\StoreUserController::class)->name('store');
        Route::get('/{user}', App\Http\Controllers\Admin\Users\ShowUserController::class)->name('show');
        Route::get('/{user}/edit', App\Http\Controllers\Admin\Users\EditUserController::class)->name('edit');
        Route::put('/{user}', App\Http\Controllers\Admin\Users\UpdateUserController::class)->name('update');
        Route::delete('/{user}', App\Http\Controllers\Admin\Users\DestroyUserController::class)->name('destroy');
    });

    // AMPDS Codes
    Route::prefix('ampds-codes')->name('ampds-codes.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\AMPDSCodes\IndexAMPDSCodesController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\AMPDSCodes\CreateAMPDSCodeController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\AMPDSCodes\StoreAMPDSCodeController::class)->name('store');
        Route::get('/{ampdsCode}', App\Http\Controllers\Admin\AMPDSCodes\ShowAMPDSCodeController::class)->name('show');
        Route::get('/{ampdsCode}/edit', App\Http\Controllers\Admin\AMPDSCodes\EditAMPDSCodeController::class)->name('edit');
        Route::put('/{ampdsCode}', App\Http\Controllers\Admin\AMPDSCodes\UpdateAMPDSCodeController::class)->name('update');
        Route::delete('/{ampdsCode}', App\Http\Controllers\Admin\AMPDSCodes\DestroyAMPDSCodeController::class)->name('destroy');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->group(function (): void {
        Route::get('/', App\Http\Controllers\Admin\Roles\IndexRolesController::class)->name('index');
        Route::get('/create', App\Http\Controllers\Admin\Roles\CreateRoleController::class)->name('create');
        Route::post('/', App\Http\Controllers\Admin\Roles\StoreRoleController::class)->name('store');
        Route::get('/{role}', App\Http\Controllers\Admin\Roles\ShowRoleController::class)->name('show');
        Route::get('/{role}/edit', App\Http\Controllers\Admin\Roles\EditRoleController::class)->name('edit');
        Route::put('/{role}', App\Http\Controllers\Admin\Roles\UpdateRoleController::class)->name('update');
        Route::delete('/{role}', App\Http\Controllers\Admin\Roles\DestroyRoleController::class)->name('destroy');
    });
});
