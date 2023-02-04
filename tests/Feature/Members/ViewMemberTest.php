<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can view a member', function () {
    authenticatedUser(['member.view']);
    $member = Member::factory()->create();

    $this->get(route('members.view', ['member' => $member]))
        ->assertViewIs('members.view')
        ->assertSee($member->name)
        ->assertSee(ucfirst($member->status));
});
