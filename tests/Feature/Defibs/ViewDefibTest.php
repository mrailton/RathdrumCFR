<?php

declare(strict_types=1);

use App\Models\Defib;

test('an authorised user can view a defib', function () {
    authenticatedUser(['defib.view']);
    $defib = Defib::factory()->create();

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee($defib->name)
        ->assertSee($defib->location);
});

test('an authorised user can not view a defib that does not exist', function () {
    authenticatedUser(['defib.view']);

    $this->get(route('defibs.view', ['defib' => 124343]))
        ->assertStatus(404);
});
