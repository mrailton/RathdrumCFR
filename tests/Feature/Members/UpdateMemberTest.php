<?php

declare(strict_types=1);

use App\Models\Member;

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
        ->assertSessionHas('success', 'Member successfully updated')
        ->assertRedirectToRoute('members.view', ['member' => $member]);

    $this->get(route('members.view', ['member' => $member]))
        ->assertSee($data['email'])
        ->assertSee($data['cfr_certificate_number']);
});
