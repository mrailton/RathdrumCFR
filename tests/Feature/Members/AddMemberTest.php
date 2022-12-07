<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can create a new member', function () {
    $data = Member::factory()->make();
    $request = authenticatedUser(['member.create', 'member.list']);

    $request->get(route('members.list'))
        ->assertSee('Add Member');

    $request->get(route('members.create'))
        ->assertSee('Add Member')
        ->assertSee('Role');

    $request->post(route('members.store'), $data->toArray())
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('members.list')
        ->assertSessionHas('success', 'New member successfully added');

    expect(Member::count())->toBe(1)
        ->and(Member::first()->name)->toBe($data->name);
});
