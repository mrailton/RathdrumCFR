<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can update a member record', function () {
    $member = Member::factory()->create();
    $data = $member->toArray();
    $data['email'] = 'updated@user.com';
    $data['cfr_certificate_number'] = 'GG1374372';
    $user = authenticatedUser(['member.view', 'member.update']);

    $user->get(route('members.view', ['id' => $member->id]))
        ->assertSee($member->email)
        ->assertSee('Update Member');

    $user->get(route('members.edit', ['id' => $member->id]))
        ->assertSee($member->email)
        ->assertSee('Update');

    $user->put(route('members.update', ['id' => $member->id]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertSessionHas('success', 'Member successfully updated')
        ->assertRedirectToRoute('members.view', ['id' => $member->id]);

    $user->get(route('members.view', ['id' => $member->id]))
        ->assertSee($data['email'])
        ->assertSee($data['cfr_certificate_number']);
});
