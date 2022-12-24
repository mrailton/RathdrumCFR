<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function login_page_renders(): void
    {
        $this->get(route('login.create'))
            ->assertSee('Rathdrum Community First Responders')
            ->assertSee('Sign in to your account')
            ->assertSee('Email address');
    }

    /** @test */
    public function an_authenticated_user_can_not_visit_the_login_page(): void
    {
        $this->actingAs($this->user())
            ->get(route('login.create'))
            ->assertStatus(302)
            ->assertRedirectToRoute('index');
    }

    /** @test */
    public function allows_a_user_to_login(): void
    {
        User::factory(['email' => 'new@user.com'])->create();

        $this->post(route('login.store'), ['email' => 'new@user.com', 'password' => 'password'])
            ->assertStatus(302)
            ->assertSessionDoesntHaveErrors();

        $this->get(route('index'))
            ->assertStatus(200)
            ->assertSee('Rathdrum Community First Responders')
            ->assertSee('Logout');
    }

    /** @test */
    public function a_non_registered_user_can_not_login(): void
    {
        $this->post(route('login.store'), ['email' => 'guest@user.com', 'password' => 'nope'])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }
}
