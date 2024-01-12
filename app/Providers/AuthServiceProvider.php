<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'RickDBCN\FilamentEmail\Models\Email' => 'App\Policies\EmailPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
