<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can view a list of members', function () {
    authenticatedUser(['member.list']);
    $members = Member::factory()->count(5)->create();

    $res = $this->get(route('members.list'));

    $res->assertSee($members[0]->name)
        ->assertSee($members[1]->phone)
        ->assertSee($members[3]->email);
});

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
        ->assertSessionHas('toasts', [[
            'message' => 'New member successfully added',
            'duration' => 3000,
            'type' => 'success'
        ]]);

    $this->assertEquals(1, Member::count());
    $this->assertEquals($data->name, Member::first()->name);
});

test('an authorised user can view a member', function () {
    authenticatedUser(['member.view']);
    $member = Member::factory()->create();

    $this->get(route('members.view', ['member' => $member]))
        ->assertViewIs('members.view')
        ->assertSee($member->name)
        ->assertSee(ucfirst($member->status));
});

test('an authorised user can update a member', function () {
    authenticatedUser(['member.view', 'member.update']);
    $member = Member::factory()->create();
    $data = $member->toArray();
    $data['email'] = 'updated@user.com';
    $data['cfr_certificate_number'] = 'GG1374372';

    $this->get(route('members.view', ['member' => $member]))
        ->assertSee($member->email)
        ->assertSee('Update Member');

    $this->get(route('members.edit', ['member' => $member]))
        ->assertSee($member->email)
        ->assertSee('Update');

    $this->put(route('members.update', ['member' => $member]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertSessionHas('toasts', [[
            'message' => 'Member successfully updated',
            'duration' => 3000,
            'type' => 'success'
        ]])
        ->assertRedirectToRoute('members.view', ['member' => $member]);

    $this->get(route('members.view', ['member' => $member]))
        ->assertSee($data['email'])
        ->assertSee($data['cfr_certificate_number']);
});
