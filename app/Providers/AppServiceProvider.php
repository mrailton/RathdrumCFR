<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\DefibInspection;
use App\Observers\DefibInspectionObserver;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public const string HOME = '/';

    public function register(): void
    {
    }

    public function boot(): void
    {
        DefibInspection::observe(DefibInspectionObserver::class);

        // Register policies for Spatie models
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
    }
}
