<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_list_of_users(): void
    {
        $this->actingAs($this->user(['user.list']));
        $users = User::factory()->count(2)->create();

        $this->get(route('users.list'))
            ->assertSee($users[0]->name)
            ->assertSee($users[1]->name);
    }
}
