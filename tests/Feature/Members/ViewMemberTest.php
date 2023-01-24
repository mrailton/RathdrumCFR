<?php

declare(strict_types=1);

namespace Tests\Feature\Members;

use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Tests\TestCase;

class ViewMemberTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    /** @test */
    public function an_authorised_user_can_view_a_member(): void
    {
        $member = Member::factory()->create();
        $this->actingAs(User::factory()->create()->givePermissionTo(['member.view']));

        $this->get(route('members.view', ['member' => $member]))
            ->assertViewIs('members.view')
            ->assertSee($member->name)
            ->assertSee(ucfirst($member->status));
    }
}
