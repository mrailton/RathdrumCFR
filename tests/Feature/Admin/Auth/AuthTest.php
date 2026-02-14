<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('login page renders', function (): void {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSee('Sign in to your account');
});

test('login with valid credentials redirects to dashboard', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

test('login with invalid credentials shows error', function (): void {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('logout redirects to home page', function (): void {
    $response = $this->actingAs($this->user)->post(route('logout'));

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('unauthenticated users cannot access admin routes', function (): void {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated users cannot access members index', function (): void {
    $response = $this->get(route('admin.members.index'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated users cannot access defibs index', function (): void {
    $response = $this->get(route('admin.defibs.index'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated users cannot access callouts index', function (): void {
    $response = $this->get(route('admin.callouts.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users are redirected away from login page', function (): void {
    $response = $this->actingAs($this->user)->get(route('login'));

    $response->assertRedirect('/');
});
