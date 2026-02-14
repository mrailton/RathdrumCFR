<?php

declare(strict_types=1);

use App\Models\Member;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('members index requires authentication', function (): void {
    $response = $this->get(route('admin.members.index'));

    $response->assertRedirect(route('login'));
});

test('members index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.members.index'));

    $response->assertOk();
    $response->assertViewIs('admin.members.index');
});

test('members index shows all members', function (): void {
    Member::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.members.index'));

    $response->assertOk();
    $response->assertViewHas('members', fn ($members) => 3 === $members->count());
});

test('members index can search by name', function (): void {
    Member::factory()->create(['name' => 'John Doe']);
    Member::factory()->create(['name' => 'Jane Smith']);

    $response = $this->actingAs($this->user)->get(route('admin.members.index', ['search' => 'John']));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertDontSee('Jane Smith');
});

test('members index can filter by status', function (): void {
    Member::factory()->create(['name' => 'Active Member', 'status' => 'active']);
    Member::factory()->create(['name' => 'Inactive Member', 'status' => 'inactive']);

    $response = $this->actingAs($this->user)->get(route('admin.members.index', ['status' => 'active']));

    $response->assertOk();
    $response->assertSee('Active Member');
    $response->assertDontSee('Inactive Member');
});

test('members index can show trashed members', function (): void {
    Member::factory()->create(['name' => 'Active Member']);
    Member::factory()->create(['name' => 'Deleted Member', 'deleted_at' => now()]);

    $response = $this->actingAs($this->user)->get(route('admin.members.index', ['trashed' => 'only']));

    $response->assertOk();
    $response->assertSee('Deleted Member');
    $response->assertDontSee('Active Member');
});

test('can view create member page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.members.create'));

    $response->assertOk();
    $response->assertViewIs('admin.members.create');
});

test('can create a new member', function (): void {
    $memberData = [
        'name' => 'Test Member',
        'status' => 'active',
        'title' => 'Responder',
        'phone' => '1234567890',
        'email' => 'test@example.com',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.members.store'), $memberData);

    $response->assertRedirect();
    $this->assertDatabaseHas('members', ['name' => 'Test Member']);
});

test('member creation requires name', function (): void {
    $memberData = [
        'status' => 'active',
        'title' => 'Responder',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.members.store'), $memberData);

    $response->assertSessionHasErrors(['name']);
});

test('member creation requires status', function (): void {
    $memberData = [
        'name' => 'Test Member',
        'title' => 'Responder',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.members.store'), $memberData);

    $response->assertSessionHasErrors(['status']);
});

test('can view a member', function (): void {
    $member = Member::factory()->create(['name' => 'Test Member']);

    $response = $this->actingAs($this->user)->get(route('admin.members.show', $member));

    $response->assertOk();
    $response->assertViewIs('admin.members.show');
    $response->assertSee('Test Member');
});

test('can view edit member page', function (): void {
    $member = Member::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.members.edit', $member));

    $response->assertOk();
    $response->assertViewIs('admin.members.edit');
});

test('can update a member', function (): void {
    $member = Member::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($this->user)->put(route('admin.members.update', $member), [
        'name' => 'New Name',
        'status' => 'active',
        'title' => 'Responder',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('members', ['id' => $member->id, 'name' => 'New Name']);
});

test('can delete a member', function (): void {
    $member = Member::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.members.destroy', $member));

    $response->assertRedirect(route('admin.members.index'));
    $this->assertSoftDeleted('members', ['id' => $member->id]);
});

test('can restore a deleted member', function (): void {
    $member = Member::factory()->create(['deleted_at' => now()]);

    $response = $this->actingAs($this->user)->patch(route('admin.members.restore', $member->id));

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('members', ['id' => $member->id, 'deleted_at' => null]);
});
