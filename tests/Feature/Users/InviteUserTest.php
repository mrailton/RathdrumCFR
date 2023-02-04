<?php

declare(strict_types=1);

use App\Mail\UserInvitationMail;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('an authorised user can invite a new user', function () {
    Mail::fake();
    authenticatedUser(['user.list', 'user.invite']);
    $this->assertDatabaseEmpty(Invite::class);

    $this->get(route('users.invite.create'))->assertSee('Invite User');
    $data = ['name' => 'Invited User', 'email' => 'invite@user.com'];
    $this->post(route('users.invite.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('users.list'));

    $this->assertDatabaseHas(Invite::class, $data);
    Mail::assertQueued(UserInvitationMail::class);
});

test('an invited user can register', function () {
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

    $this->assertDatabaseHas(User::class, ['name' => $invite->name, 'email' => $invite->email]);
    $this->assertNotNull($invite->fresh()->registered_at);
});

test('an invite can not use the same email as an active invite', function () {
    authenticatedUser(['user.list', 'user.invite']);
    $existing = Invite::factory()->create();
    $this->assertDatabaseCount(Invite::class, 1);

    $this->post(route('users.invite.store'), ['name' => $existing->name, 'email' => $existing->email])
        ->assertSessionHasErrors(['email' => 'This email address is used in a pending invite']);

    $this->assertDatabaseCount(Invite::class, 1);
});

test('an invite can use the same email as an expired invite if the user is not registered', function () {
    authenticatedUser(['user.list', 'user.invite']);
    $existing = Invite::factory()->create();
    $existing->expires_at = now()->subHours(2);
    $existing->save();

    $this->assertDatabaseCount(Invite::class, 1);

    $this->post(route('users.invite.store'), ['name' => $existing->name, 'email' => $existing->email])
        ->assertSessionDoesntHaveErrors(['email' => 'This email address is used in a pending invite']);

    $this->assertDatabaseCount(Invite::class, 2);
});

test('an invite can not be created for the email address of an existing user', function () {
    authenticatedUser(['user.list', 'user.invite']);
    $user = User::factory()->create();
    $this->assertDatabaseCount(Invite::class, 0);

    $this->post(route('users.invite.store'), ['name' => 'Test User', 'email' => $user->email])
        ->assertSessionHasErrors(['email']);

    $this->assertDatabaseCount(Invite::class, 0);
});

test('an expired invite can not be used to register', function () {
    $invite = Invite::factory()->create();
    $invite->expires_at = now()->subHours(2);
    $invite->save();
    $this->assertDatabaseCount(User::class, 1);

    $this->get(route('register.create', ['invite' => $invite->token]))
        ->assertStatus(404)
        ->assertDontSee('Register');

    $this->post(route('register.store'), ['name' => 'Test User', 'email' => 'test@user.com', 'password' => 'password_123', 'password_confirmation' => 'password_123', 'token' => $invite->token])
        ->assertStatus(400);
    $this->assertDatabaseCount(User::class, 1);
});
