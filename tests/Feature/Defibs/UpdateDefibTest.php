<?php

use App\Models\Defib;

test('an authorised user can update a defib', function () {
    $defib = Defib::factory()->create();
    $data = $defib->toArray();
    $data['name'] = 'Updated Defib';
    $request = authenticatedUser(['defib.update', 'defib.view']);

    $request
        ->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee('Update Defib');

    $request
        ->get(route('defibs.edit', ['id' => $defib->id]))
        ->assertSee($defib->name);

    $request
        ->put(route('defibs.update', ['id' => $defib->id]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['id' => $defib->id]);

    $request
        ->get(route('defibs.edit', ['id' => $defib->id]))
        ->assertSee($data['name']);

    expect($defib->fresh())->name->toBe($data['name']);
});
