<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can add a note to a member', function () {
    authenticatedUser(['member.view', 'member.note']);
    $data = ['note' => 'Test Note'];
    $member = Member::factory()->create();

    $this->get(route('members.view', ['member' => $member]))
        ->assertSee('Add Note');

    $this->get(route('members.notes.create', ['member' => $member]))
        ->assertSee('Note')
        ->assertSee('Add Member Note');

    $this->post(route('members.notes.store', ['member' => $member]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('members.view', ['member' => $member])
        ->assertSessionHas('success', 'Member note successfully added');

    $this->get(route('members.view', ['member' => $member]))
        ->assertSee($data['note']);
});
