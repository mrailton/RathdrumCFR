<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\DefibInspection;
use App\Observers\DefibInspectionObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        DefibInspection::observe(DefibInspectionObserver::class);
    }
}
