<?php

declare(strict_types=1);

use App\Models\User;

test('an authenticated user can logout', function () {
    $this->actingAs(User::factory()->create());

    $this->assertAuthenticated();

    $this->post(route('auth.logout'))
        ->assertRedirectToRoute('index')
        ->assertSessionHas('Success', 'You have been successfully logged out');

    $this->assertGuest();
});
