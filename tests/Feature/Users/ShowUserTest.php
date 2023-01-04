<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_users_details(): void
    {
        $this->actingAs($this->user(['user.view']));
        $user = User::factory()->create();

        $this->get(route('users.show', ['user' => $user]))
            ->assertSee($user->name)
            ->assertSee($user->email);
    }
}
