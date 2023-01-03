<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function an_authenticated_user_can_logout(): void
    {
        $this->actingAs($this->user());

        $this->assertAuthenticated();

        $this->post(route('auth.logout'))
            ->assertRedirectToRoute('index')
            ->assertSessionHas('Success', 'You have been successfully logged out');

        $this->assertGuest();
    }
}
