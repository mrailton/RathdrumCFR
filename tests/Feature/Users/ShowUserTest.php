<?php

declare(strict_types=1);

use App\Models\User;

test('an authorised user can view a users details', function () {
    authenticatedUser(['user.view']);
    $user = User::factory()->create();

    $this->get(route('users.show', ['user' => $user]))
        ->assertSee($user->name)
        ->assertSee($user->email);
});
