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
}
