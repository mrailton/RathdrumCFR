<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\EmailPolicy;
use App\Policies\QueueMonitorPolicy;
use Croustibat\FilamentJobsMonitor\Models\QueueMonitor;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use RickDBCN\FilamentEmail\Models\Email;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Email::class => EmailPolicy::class,
        QueueMonitor::class => QueueMonitorPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
