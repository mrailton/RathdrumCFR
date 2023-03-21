<?php

declare(strict_types=1);

use App\Models\Defib;

test('an authorised user can create a new defib note', function () {
    $defib = Defib::factory()->create();
    authenticatedUser(['defib.view', 'defib.note', 'defib.list']);

    $noteData = [
        'note' => fake()->sentence(),
    ];

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee('Add Note');

    $this->get(route('defibs.notes.create', ['defib' => $defib->id]))
        ->assertSee('Add Defib Note')
        ->assertSee('Add Note');

    $this->post(route('defibs.notes.store', ['defib' => $defib->id]), $noteData)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['defib' => $defib->id]);

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee($noteData['note'])
        ->assertSee(auth()->user()->name);
});
