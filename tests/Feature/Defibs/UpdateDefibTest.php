<?php

declare(strict_types=1);

use App\Models\Defib;

test('an authorised user can update a defib', function () {
    authenticatedUser(['defib.update', 'defib.view']);
    $defib = Defib::factory()->create();
    $data = $defib->toArray();
    $data['name'] = 'Updated Defib';

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee('Update Defib');

    $this->get(route('defibs.edit', ['defib' => $defib->id]))
        ->assertSee($defib->name);

    $this->put(route('defibs.update', ['defib' => $defib->id]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['defib' => $defib->id]);

    $this->get(route('defibs.edit', ['defib' => $defib->id]))
        ->assertSee($data['name']);

    $this->assertEquals($data['name'], $defib->fresh()->name);
});
