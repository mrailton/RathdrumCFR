<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\DefibInspected;
use App\Listeners\SendDefibInspectedMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DefibInspected::class => [
            SendDefibInspectedMail::class,
        ],
    ];

    public function boot(): void
    {
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
