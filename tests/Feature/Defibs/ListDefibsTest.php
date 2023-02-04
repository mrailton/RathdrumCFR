<?php

declare(strict_types=1);

use App\Models\Defib;

test('a user with permission can view a list of defibs', function () {
    authenticatedUser(['defib.list']);
    $defibs = Defib::factory()->count(2)->create();

    $this->get(route('defibs.list'))
        ->assertStatus(200)
        ->assertSee($defibs[0]->name)
        ->assertSee($defibs[1]->name);
});

test('a guest can not view a list of defibs', function () {
    guest();
    Defib::factory()->count(2)->create();

    $this->get(route('defibs.list'))
        ->assertStatus(302)
        ->assertRedirectToRoute('login.create');
});

test('a user without permission can not view a list of defibs', function () {
    authenticatedUser([]);
    $defibs = Defib::factory()->count(2)->create();

    $this->get(route('defibs.list'))
        ->assertStatus(403)
        ->assertDontSee($defibs[0]->name)
        ->assertDontSee($defibs[1]->name);
});
