<?php

declare(strict_types=1);

use App\Models\User;

test('login page renders', function () {
    $this->get(route('login.create'))
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Sign in to your account')
        ->assertSee('Email address');
});

test('an authenticated user can not visit the login page', function () {
    authenticatedUser()->get(route('login.create'))
        ->assertStatus(302)
        ->assertRedirectToRoute('index');
});

test('a registered user can log in', function () {
    User::factory(['email' => 'new@user.com'])->create();

    $this->post(route('login.store'), ['email' => 'new@user.com', 'password' => 'password'])
        ->assertStatus(302)
        ->assertSessionDoesntHaveErrors();

    $this->get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Logout');
});

test('a user can not login with an invalid email address', function () {
    $this->post(route('login.store'), ['email' => 'guest@user.com', 'password' => 'nope'])
        ->assertStatus(302)
        ->assertSessionHasErrors('email');
});

test('an authenticated user can logout', function () {
    $this->actingAs(User::factory()->create());

    $this->assertAuthenticated();

    $this->post(route('auth.logout'))
        ->assertRedirectToRoute('index')
        ->assertSessionHas('Success', 'You have been successfully logged out');

    $this->assertGuest();
});
