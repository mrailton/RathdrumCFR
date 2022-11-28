<?php

use App\Models\Defib;

test('an authorised user can view a defib', function () {
    $defib = Defib::factory()->create();

    authenticatedUser(['defib.view'])
        ->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee($defib->name)
        ->assertSee($defib->location);
});

test('an authorised user can not view a defib that does not exist', function () {
    authenticatedUser(['defib.view'])
        ->get(route('defibs.view', ['id' => 124343]))
        ->assertStatus(404);
});
