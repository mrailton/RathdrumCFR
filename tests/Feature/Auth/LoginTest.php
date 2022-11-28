<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\get;

test('login page renders', function () {
    guest()
        ->get(route('login'))
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Sign in to your account')
        ->assertSee('Email address');
});

test('an authenticated user can not visit the login page', function () {
    authenticatedUser()
        ->get(route('login'))
        ->assertStatus(302)
        ->assertRedirectToRoute('index');
});

it('allows a user to login', function () {
    $user = User::factory()->create();

    guest()
        ->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertStatus(302)
        ->assertSessionDoesntHaveErrors();

    get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Logout');
});

it('does not allow a non-registered user to login', function () {
    guest()
        ->post(route('login'), ['email' => 'guest@user.com', 'password' => 'nope'])
        ->assertStatus(302)
        ->assertSessionHasErrors('email');
});
