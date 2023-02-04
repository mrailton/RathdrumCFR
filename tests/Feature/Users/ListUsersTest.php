<?php

declare(strict_types=1);

use App\Models\User;

test('an authorised user can view a list of users', function () {
    authenticatedUser(['user.list']);
    $users = User::factory()->count(2)->create();

    $this->get(route('users.list'))
        ->assertSee($users[0]->name)
        ->assertSee($users[1]->name);
});
