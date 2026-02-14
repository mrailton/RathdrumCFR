<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder is idempotent - safe to run on each release.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = $this->getPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Clean up orphaned permissions that are no longer defined
        Permission::whereNotIn('name', $permissions)
            ->where('guard_name', 'web')
            ->delete();
    }

    /**
     * Get all permissions for the application.
     *
     * To add a new permission:
     * 1. Add it to the appropriate section below
     * 2. Run: php artisan db:seed --class=PermissionSeeder
     *
     * @return array<int, string>
     */
    private function getPermissions(): array
    {
        return [
            // Member permissions
            'view_any_member',
            'view_member',
            'create_member',
            'update_member',
            'delete_member',
            'restore_member',

            // Callout permissions
            'view_any_callout',
            'view_callout',
            'create_callout',
            'update_callout',
            'delete_callout',

            // Defib permissions
            'view_any_defib',
            'view_defib',
            'create_defib',
            'update_defib',
            'delete_defib',
            'restore_defib',

            // Training Session permissions
            'view_any_training_session',
            'view_training_session',
            'create_training_session',
            'update_training_session',
            'delete_training_session',

            // User permissions
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',

            // AMPDS Code permissions
            'view_any_ampds_code',
            'view_ampds_code',
            'create_ampds_code',
            'update_ampds_code',
            'delete_ampds_code',

            // Role permissions (for managing roles via admin UI)
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',

            // Permission permissions (view only - permissions are seeded)
            'view_any_permission',
        ];
    }
}
