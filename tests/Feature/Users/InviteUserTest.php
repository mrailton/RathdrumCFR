<?php

namespace Tests\Feature\Users;

use App\Mail\UserInvitationMail;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteUserTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_invite_a_new_user(): void
    {
        Mail::fake();
        $this->actingAs($this->user(['user.list', 'user.invite']));
        $this->assertDatabaseEmpty(Invite::class);

        $this->get(route('users.invite.create'))->assertSee('Invite User');
        $data = ['name' => 'Invited User', 'email' => 'invite@user.com'];
        $this->post(route('users.invite.store'), $data)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('users.list'));

        $this->assertDatabaseHas(Invite::class, $data);
        Mail::assertQueued(UserInvitationMail::class);
    }

    /** @test */
    public function an_invited_user_can_register(): void
    {
        $invite = Invite::factory()->create();
        $data = ['name' => $invite->name, 'email' => $invite->email, 'password' => 'password123', 'password_confirmation' => 'password123', 'token' => $invite->token];

        $this->assertDatabaseMissing(User::class, ['name' => $invite->name, 'email' => $invite->email]);

        $this->get(route('register.create', ['invite' => $invite->token]))
            ->assertSee($invite->name)
            ->assertSee($invite->email)
            ->assertSee('Register');

        $this->post(route('register.store'), $data)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('login.create'));

        $this->assertDatabaseHas(User::class, ['name'=> $invite->name, 'email' => $invite->email]);
        $this->assertNotNull($invite->fresh()->registered_at);
    }

    /** @test */
    public function an_invite_can_not_use_the_same_email_as_an_active_invite(): void
    {
        $existing = Invite::factory()->create();
        $this->actingAs($this->user(['user.list', 'user.invite']));
        $this->assertDatabaseCount(Invite::class, 1);

        $this->post(route('users.invite.store'), ['name' => $existing->name, 'email' => $existing->email])
            ->assertSessionHasErrors(['email' => 'This email address is used in a pending invite']);

        $this->assertDatabaseCount(Invite::class, 1);
    }

    /** @test */
    public function an_invite_can_use_the_same_email_as_an_expired_invite_if_user_not_registered(): void
    {
        $this->actingAs($this->user(['user.list', 'user.invite']));
        $existing = Invite::factory()->create();
        $existing->expires_at = now()->subHours(2);
        $existing->save();

        $this->assertDatabaseCount(Invite::class, 1);

        $this->post(route('users.invite.store'), ['name' => $existing->name, 'email' => $existing->email])
            ->assertSessionDoesntHaveErrors(['email' => 'This email address is used in a pending invite']);

        $this->assertDatabaseCount(Invite::class, 2);
    }

    /** @test */
    public function an_invite_can_not_be_created_for_the_email_address_of_an_existing_user(): void
    {
        $this->actingAs($this->user(['user.list', 'user.invite']));
        $user = User::factory()->create();
        $this->assertDatabaseCount(Invite::class, 0);

        $this->post(route('users.invite.store'), ['name' => 'Test User', 'email' => $user->email])
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount(Invite::class, 0);
    }

    /** @test */
    public function an_expired_invite_can_not_be_used_to_register(): void
    {
        $invite = Invite::factory()->create();
        $invite->expires_at = now()->subHours(2);
        $invite->save();

        $this->get(route('register.create', ['invite' => $invite->token]))
            ->assertStatus(404)
            ->assertDontSee('Register');
    }
}
