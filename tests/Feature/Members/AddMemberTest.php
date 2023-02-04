<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can create a new member', function () {
    authenticatedUser(['member.create', 'member.list']);
    $data = Member::factory()->make();

    $this->get(route('members.list'))
        ->assertSee('Add Member');

    $this->get(route('members.create'))
        ->assertSee('Add Member')
        ->assertSee('Role');

    $this->post(route('members.store'), $data->toArray())
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('members.list')
        ->assertSessionHas('success', 'New member successfully added');

    $this->assertEquals(1, Member::count());
    $this->assertEquals($data->name, Member::first()->name);
});
