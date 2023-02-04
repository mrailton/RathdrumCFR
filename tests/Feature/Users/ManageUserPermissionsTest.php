<?php

declare(strict_types=1);

use App\Models\User;

test('an authorised user can view a users permissions', function () {
    authenticatedUser(['user.view',  'user.permissions']);
    $user = User::factory()->create();

    $this->get(route('users.show', ['user' => $user]))
        ->assertSee('Manage Permissions');

    $this->get(route('users.permissions.show', ['user' => $user]))
        ->assertSee('Manage Permissions for ' . $user->name);
});
