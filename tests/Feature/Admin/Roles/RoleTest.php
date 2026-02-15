<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'view_any_role', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'view_role', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'create_role', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'update_role', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'delete_role', 'guard_name' => 'web']);
});

it('roles index requires authentication', function (): void {
    $response = $this->get(route('admin.roles.index'));
    $response->assertRedirect(route('login'));
});

it('roles index displays for authenticated users with permission', function (): void {
    $user = createAdminUser();

    $response = $this->actingAs($user)->get(route('admin.roles.index'));

    $response->assertOk();
    $response->assertViewIs('admin.roles.index');
});

it('roles index shows all roles', function (): void {
    $user = createAdminUser();
    Role::firstOrCreate(['name' => 'Test Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->get(route('admin.roles.index'));

    $response->assertOk();
    $response->assertSee('Test Role');
});

it('roles index can search', function (): void {
    $user = createAdminUser();
    Role::firstOrCreate(['name' => 'Searchable Role', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'Other Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->get(route('admin.roles.index', ['search' => 'Searchable']));

    $response->assertOk();
    $response->assertSee('Searchable Role');
});

it('can view create role page', function (): void {
    $user = createAdminUser();

    $response = $this->actingAs($user)->get(route('admin.roles.create'));

    $response->assertOk();
    $response->assertViewIs('admin.roles.create');
});

it('can create a new role', function (): void {
    $user = createAdminUser();
    $permission = Permission::firstOrCreate(['name' => 'view_any_member', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->post(route('admin.roles.store'), [
        'name' => 'New Test Role',
        'permissions' => [$permission->name],
    ]);

    $role = Role::where('name', 'New Test Role')->first();
    $response->assertRedirect(route('admin.roles.show', $role));
    expect($role)->not->toBeNull();
    expect($role->hasPermissionTo('view_any_member'))->toBeTrue();
});

it('role creation validates required fields', function (): void {
    $user = createAdminUser();

    $response = $this->actingAs($user)->post(route('admin.roles.store'), []);

    $response->assertSessionHasErrors(['name']);
});

it('role creation validates unique name', function (): void {
    $user = createAdminUser();
    Role::firstOrCreate(['name' => 'Existing Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->post(route('admin.roles.store'), [
        'name' => 'Existing Role',
    ]);

    $response->assertSessionHasErrors(['name']);
});

it('can view a role', function (): void {
    $user = createAdminUser();
    $role = Role::firstOrCreate(['name' => 'View Test Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->get(route('admin.roles.show', $role));

    $response->assertOk();
    $response->assertSee('View Test Role');
});

it('can view edit role page', function (): void {
    $user = createAdminUser();
    $role = Role::firstOrCreate(['name' => 'Edit Test Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->get(route('admin.roles.edit', $role));

    $response->assertOk();
    $response->assertViewIs('admin.roles.edit');
});

it('can update a role', function (): void {
    $user = createAdminUser();
    $role = Role::firstOrCreate(['name' => 'Update Test Role', 'guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'create_member', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->put(route('admin.roles.update', $role), [
        'name' => 'Updated Role Name',
        'permissions' => [$permission->name],
    ]);

    $response->assertRedirect(route('admin.roles.show', $role));
    $role->refresh();
    expect($role->name)->toBe('Updated Role Name');
    expect($role->hasPermissionTo('create_member'))->toBeTrue();
});

it('can delete a role', function (): void {
    $user = createAdminUser();
    $role = Role::create(['name' => 'Delete Test Role', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->delete(route('admin.roles.destroy', $role));

    $response->assertRedirect(route('admin.roles.index'));
    expect(Role::where('name', 'Delete Test Role')->exists())->toBeFalse();
});

it('cannot delete admin role', function (): void {
    $user = createAdminUser();
    $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

    $response = $this->actingAs($user)->delete(route('admin.roles.destroy', $adminRole));

    $response->assertRedirect(route('admin.roles.index'));
    $response->assertSessionHas('error');
    expect(Role::where('name', 'Admin')->exists())->toBeTrue();
});

it('unauthorized user cannot access roles', function (): void {
    // Create a user without role permissions
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Limited', 'guard_name' => 'web']);
    // Give only member view permission
    $permission = Permission::firstOrCreate(['name' => 'view_any_member', 'guard_name' => 'web']);
    $role->syncPermissions([$permission]);
    $user->assignRole($role);

    $response = $this->actingAs($user)->get(route('admin.roles.index'));

    $response->assertForbidden();
});
