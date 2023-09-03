<?php

declare(strict_types=1);

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::get('/contact', [ContactFormController::class, 'show'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'process'])->name('contact.store');
