<?php

declare(strict_types=1);

namespace Tests\Feature\Members;

use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Tests\TestCase;

class ListMembersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    /** @test */
    public function an_authorised_user_can_view_a_list_of_members(): void
    {
        $members = Member::factory()->count(5)->create();
        $this->actingAs(User::factory()->create()->givePermissionTo(['member.list']));

        $res = $this->get(route('members.list'));

        $res->assertSee($members[0]->name)
            ->assertSee($members[1]->phone)
            ->assertSee($members[3]->email);
    }
}
