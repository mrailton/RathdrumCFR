<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class ManageUserPermissionsTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_users_permissions(): void
    {
        $this->actingAs($this->user(['user.view',  'user.permissions']));
        $user = User::factory()->create();

        $this->get(route('users.show', ['user' => $user]))
            ->assertSee('Manage Permissions');

        $this->get(route('users.permissions.show', ['user' => $user]))
            ->assertSee('Manage Permissions for ' . $user->name);
    }
}
