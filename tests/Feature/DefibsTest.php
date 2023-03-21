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
