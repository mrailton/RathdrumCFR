<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('users index requires authentication', function (): void {
    $response = $this->get(route('admin.users.index'));

    $response->assertRedirect(route('login'));
});

test('users index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertViewIs('admin.users.index');
});

test('users index shows all users', function (): void {
    User::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertViewHas('users', function ($users) {
        // +1 for the admin user created in beforeEach
        return 4 === $users->count();
    });
});

test('users index can search by name', function (): void {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    $response = $this->actingAs($this->user)->get(route('admin.users.index', ['search' => 'John']));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertDontSee('Jane Smith');
});

test('users index can search by email', function (): void {
    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    $response = $this->actingAs($this->user)->get(route('admin.users.index', ['search' => 'john@example']));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertDontSee('Jane Smith');
});

test('can view create user page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.users.create'));

    $response->assertOk();
    $response->assertViewIs('admin.users.create');
});

test('can create a new user', function (): void {
    $userData = [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.users.store'), $userData);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
    ]);
});

test('user creation validates required fields', function (): void {
    $response = $this->actingAs($this->user)->post(route('admin.users.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('user creation validates unique email', function (): void {
    User::factory()->create(['email' => 'existing@example.com']);

    $userData = [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.users.store'), $userData);

    $response->assertSessionHasErrors(['email']);
});

test('can view a user', function (): void {
    $user = User::factory()->create(['name' => 'Test User']);

    $response = $this->actingAs($this->user)->get(route('admin.users.show', $user));

    $response->assertOk();
    $response->assertViewIs('admin.users.show');
    $response->assertSee('Test User');
});

test('can view edit user page', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.users.edit', $user));

    $response->assertOk();
    $response->assertViewIs('admin.users.edit');
});

test('can update a user', function (): void {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

    $response = $this->actingAs($this->user)->put(route('admin.users.update', $user), [
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);
});

test('can delete a user', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
