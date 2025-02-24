<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\DefibInspection;
use App\Observers\DefibInspectionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const string HOME = '/';

    public function register(): void
    {
    }

    public function boot(): void
    {
        DefibInspection::observe(DefibInspectionObserver::class);
    }
}
