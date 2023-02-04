<?php

declare(strict_types=1);

use App\Models\Defib;

test('an authorised user can add a new defib', function () {
    authenticatedUser(['defib.create']);
    $data = Defib::factory()->make()->toArray();
    $this->assertDatabaseCount(Defib::class, 0);

    $this->get(route('defibs.create'))
        ->assertSee('Add Defib')
        ->assertSee('Battery Expires On');

    $this->post(route('defibs.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.list');

    $this->assertDatabaseCount(Defib::class, 1);
    $this->assertEquals($data['name'], Defib::first()->name);
});
