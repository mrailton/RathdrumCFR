<?php

declare(strict_types=1);

use App\Models\Member;

test('an authorised user can view a member', function () {
    $member = Member::factory()->create();

    authenticatedUser(['member.view'])
        ->get(route('members.view', ['id' => $member->id]))
        ->assertViewIs('members.view')
        ->assertSee($member->name)
        ->assertSee(ucfirst($member->status));
});
