<?php

use App\Models\Defib;

use function Pest\Faker\faker;

test('an authorised user can create a new defib note', function () {
    $defib = Defib::factory()->create();
    $request = authenticatedUser(['defib.view', 'defib.note', 'defib.list']);

    $noteData = [
        'note' => faker()->sentence(),
    ];

    $request->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee('Add Note');

    $request->get(route('defibs.notes.create', ['id' => $defib->id]))
        ->assertSee('Add Defib Note')
        ->assertSee('Add Note');

    $request->post(route('defibs.notes.store', ['id' => $defib->id]), $noteData)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['id' => $defib->id]);

    $request->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee($noteData['note']);
});
