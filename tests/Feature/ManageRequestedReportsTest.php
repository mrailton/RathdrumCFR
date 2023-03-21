<?php

declare(strict_types=1);

use App\Models\User;

test('an authorised user can modify the reports they receive', function () {
    authenticatedUser(['user.view', 'user.update']);
    $user = User::first();

    $this->get(route('users.show', ['user' => $user]))
        ->assertSee('Requested Reports');

    $this->get(route('users.reports.show', ['user' => $user]))
        ->assertSee('User Requested Reports')
        ->assertSee('Update');

    $this->put(route('users.reports.store', ['user' => $user]), ['cfr_cert_expiry' => 'Yes'])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('users.show', ['user' => $user]);
});
