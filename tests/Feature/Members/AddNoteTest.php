<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can add a note to a member record', function () {
    $data = ['note' => 'Test Note'];
    $member = Member::factory()->create();
    $user = authenticatedUser(['member.view', 'member.note']);

    $user->get(route('members.view', ['id' => $member->id]))
        ->assertSee('Add Note');

    $user->get(route('members.notes.create', ['id' => $member->id]))
        ->assertSee('Note')
        ->assertSee('Add Member Note');

    $user->post(route('members.notes.store', ['id' => $member->id]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('members.view', ['id' => $member->id])
        ->assertSessionHas('success', 'Member note successfully added');;

    $user->get(route('members.view', ['id' => $member->id]))
        ->assertSee($data['note']);
});
