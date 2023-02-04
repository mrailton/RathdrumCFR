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
