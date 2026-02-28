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
        $this->app->booted(function () {
            config([
                'database.connections.mysql.dump' => [
                    'dump_binary_path' => env('MYSQL_DUMP_BINARY_PATH', '/usr/bin'),
                ],
            ]);
        });
    }

    public function boot(): void
    {
        DefibInspection::observe(DefibInspectionObserver::class);
    }
}
