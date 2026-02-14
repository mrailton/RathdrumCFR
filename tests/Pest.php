<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class)->in('Feature');
uses(LazilyRefreshDatabase::class)->in('Feature');

/**
 * Create an admin user with full permissions for testing
 */
function createAdminUser(): User
{
    $user = User::factory()->create();

    // Create Admin role if it doesn't exist
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

    // Assign all permissions to Admin role
    $permissions = [
        'view_any_member', 'view_member', 'create_member', 'update_member', 'delete_member', 'restore_member',
        'view_any_callout', 'view_callout', 'create_callout', 'update_callout', 'delete_callout',
        'view_any_defib', 'view_defib', 'create_defib', 'update_defib', 'delete_defib', 'restore_defib',
        'view_any_training_session', 'view_training_session', 'create_training_session', 'update_training_session', 'delete_training_session',
        'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
        'view_any_ampds_code', 'view_ampds_code', 'create_ampds_code', 'update_ampds_code', 'delete_ampds_code',
        'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
        'view_any_permission',
    ];

    foreach ($permissions as $permissionName) {
        Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
    }

    $role->syncPermissions($permissions);
    $user->assignRole($role);

    return $user;
}

function authenticatedUser(): Tests\TestCase
{
    $user = createAdminUser();
    return test()->actingAs($user);
}

function guest()
{
    return test();
}
