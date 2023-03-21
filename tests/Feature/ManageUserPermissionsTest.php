<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Permission;

test('an authorised user can update a users permissions', function () {
    authenticatedUser(['user.view', 'user.permissions']);
    $user = User::factory()->create();

    $this->get(route('users.show', ['user' => $user]))
        ->assertSee('Manage Permissions');

    $this->get(route('users.permissions.show', ['user' => $user]))
        ->assertSee('Manage Permissions for '.$user->name);

    $permissions = Permission::inRandomOrder()->limit(5)->get()->map(function ($permission) {
        return $permission->id;
    });

    $data = ['permissions' => $permissions->toArray()];

    $this->put(route('users.permissions.update', ['user' => $user]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('users.show', ['user' => $user]);
});
