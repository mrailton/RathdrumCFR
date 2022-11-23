<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

test('login page renders', function () {
    get(route('login'))
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('Sign in to your account')
        ->assertSee('Email address');
});

test('an authenticated user can not visit the login page', function () {
    actingAs(User::factory()->create());

    get(route('login'))
        ->assertStatus(302)
        ->assertRedirectToRoute('index');
});
