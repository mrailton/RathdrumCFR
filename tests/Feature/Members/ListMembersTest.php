<?php

use App\Models\Member;

test('an authorised user can view a list of members', function () {
    $members = Member::factory()->count(5)->create();

    authenticatedUser(['member.list'])
        ->get(route('members.list'))
        ->assertSee($members[0]->name)
        ->assertSee($members[1]->phone)
        ->assertSee($members[3]->email);
});
