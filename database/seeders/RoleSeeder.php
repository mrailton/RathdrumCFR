<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder is idempotent - safe to run on each release.
     * Creates only the Admin role with all permissions.
     * Additional roles should be created via the admin UI.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Admin role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web']
        );

        // Admin gets all permissions
        $allPermissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();
        $adminRole->syncPermissions($allPermissions);
    }
}
