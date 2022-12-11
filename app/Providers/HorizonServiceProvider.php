<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Horizon::routeSlackNotificationsTo(config('services.slack.webhook_url'), '#server-alerts');
        Horizon::night();
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function (User $user) {
            return $user->can('horizon');
        });
    }
}
