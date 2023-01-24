<?php

declare(strict_types=1);

namespace Tests\Feature\Members;

use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Tests\TestCase;

class UpdateMemberTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    /** @test */
    public function an_authorised_user_can_update_a_member_record(): void
    {
        $member = Member::factory()->create();
        $data = $member->toArray();
        $data['email'] = 'updated@user.com';
        $data['cfr_certificate_number'] = 'GG1374372';
        $this->actingAs(User::factory()->create()->givePermissionTo(['member.view', 'member.update']));

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
    }
}
