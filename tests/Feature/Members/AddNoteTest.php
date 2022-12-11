<?php

declare(strict_types=1);

namespace Tests\Feature\Members;

use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Tests\TestCase;

class AddNoteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    /** @test */
    public function an_authorised_user_can_add_a_note_to_a_member_record(): void
    {
        $data = ['note' => 'Test Note'];
        $member = Member::factory()->create();
        $this->actingAs(User::factory()->create()->givePermissionTo(['member.view', 'member.note']));

        $this->get(route('members.view', ['id' => $member->id]))
            ->assertSee('Add Note');

        $this->get(route('members.notes.create', ['id' => $member->id]))
            ->assertSee('Note')
            ->assertSee('Add Member Note');

        $this->post(route('members.notes.store', ['id' => $member->id]), $data)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('members.view', ['id' => $member->id])
            ->assertSessionHas('success', 'Member note successfully added');;

        $this->get(route('members.view', ['id' => $member->id]))
            ->assertSee($data['note']);
    }
}

