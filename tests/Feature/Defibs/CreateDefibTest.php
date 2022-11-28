<?php

use App\Models\Defib;

test('an authorised user can add a new defib', function () {
    $data = Defib::factory()->make()->toArray();
    expect(Defib::count())->toBe(0);

    authenticatedUser(['defib.create'])
        ->get(route('defibs.create'))
        ->assertSee('Add Defib')
        ->assertSee('Battery Expires On');

    authenticatedUser(['defib.create'])
        ->post(route('defibs.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.list');

    expect(Defib::count())->toBe(1)
        ->and(Defib::first()->name)->toBe($data['name']);
});
