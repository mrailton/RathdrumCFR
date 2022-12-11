<?php

declare(strict_types=1);

namespace Tests\Feature\Members;

use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Tests\TestCase;

class AddMemberTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    /** @test */
    public function an_authorised_user_can_create_a_new_member(): void
    {
        $data = Member::factory()->make();
        $this->actingAs(User::factory()->create()->givePermissionTo(['member.create', 'member.list']));

        $this->get(route('members.list'))
            ->assertSee('Add Member');

        $this->get(route('members.create'))
            ->assertSee('Add Member')
            ->assertSee('Role');

        $this->post(route('members.store'), $data->toArray())
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('members.list')
            ->assertSessionHas('success', 'New member successfully added');

        $this->assertEquals(1, Member::count());
        $this->assertEquals($data->name, Member::first()->name);
    }
}
