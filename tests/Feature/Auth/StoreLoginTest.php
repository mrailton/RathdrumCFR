<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('allows a user to login', function () {
    $user = User::factory()->create();

    post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertStatus(302)
        ->assertSessionDoesntHaveErrors();

    get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Logout');
});

it('does not allow a non-registered user to login', function () {
    post(route('login'), ['email' => 'guest@user.com', 'password' => 'nope'])
        ->assertStatus(302)
        ->assertSessionHasErrors('email');
});
