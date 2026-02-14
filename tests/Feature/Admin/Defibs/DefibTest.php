<?php

declare(strict_types=1);

use App\Models\Defib;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('defibs index requires authentication', function (): void {
    $response = $this->get(route('admin.defibs.index'));

    $response->assertRedirect(route('login'));
});

test('defibs index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.defibs.index'));

    $response->assertOk();
    $response->assertViewIs('admin.defibs.index');
});

test('defibs index shows all defibs', function (): void {
    Defib::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.defibs.index'));

    $response->assertOk();
    $response->assertViewHas('defibs', fn ($defibs) => 3 === $defibs->count());
});

test('defibs index can search by name', function (): void {
    Defib::factory()->create(['name' => 'Community Center AED']);
    Defib::factory()->create(['name' => 'Sports Hall AED']);

    $response = $this->actingAs($this->user)->get(route('admin.defibs.index', ['search' => 'Community']));

    $response->assertOk();
    $response->assertSee('Community Center AED');
    $response->assertDontSee('Sports Hall AED');
});

test('defibs index can search by location', function (): void {
    Defib::factory()->create(['name' => 'AED 1', 'location' => 'Main Street']);
    Defib::factory()->create(['name' => 'AED 2', 'location' => 'Oak Avenue']);

    $response = $this->actingAs($this->user)->get(route('admin.defibs.index', ['search' => 'Main Street']));

    $response->assertOk();
    $response->assertSee('AED 1');
    $response->assertDontSee('AED 2');
});

test('defibs index can show trashed defibs', function (): void {
    Defib::factory()->create(['name' => 'Active Defib']);
    Defib::factory()->create(['name' => 'Deleted Defib', 'deleted_at' => now()]);

    $response = $this->actingAs($this->user)->get(route('admin.defibs.index', ['trashed' => 'only']));

    $response->assertOk();
    $response->assertSee('Deleted Defib');
    $response->assertDontSee('Active Defib');
});

test('can view create defib page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.defibs.create'));

    $response->assertOk();
    $response->assertViewIs('admin.defibs.create');
});

test('can create a new defib with required fields', function (): void {
    $defibData = [
        'name' => 'Test AED',
        'location' => '123 Main Street',
        'model' => 'iPad CU-SP1',
        'owner' => 'RathdrumCFR',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.defibs.store'), $defibData);

    $response->assertRedirect();
    $this->assertDatabaseHas('defibs', ['name' => 'Test AED']);
});

test('defib creation validates required fields', function (): void {
    $response = $this->actingAs($this->user)->post(route('admin.defibs.store'), []);

    $response->assertSessionHasErrors(['name', 'location', 'model', 'owner']);
});

test('can view a defib', function (): void {
    $defib = Defib::factory()->create(['name' => 'Test AED']);

    $response = $this->actingAs($this->user)->get(route('admin.defibs.show', $defib));

    $response->assertOk();
    $response->assertViewIs('admin.defibs.show');
    $response->assertSee('Test AED');
});

test('can view edit defib page', function (): void {
    $defib = Defib::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.defibs.edit', $defib));

    $response->assertOk();
    $response->assertViewIs('admin.defibs.edit');
});

test('can update a defib', function (): void {
    $defib = Defib::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($this->user)->put(route('admin.defibs.update', $defib), [
        'name' => 'New Name',
        'location' => $defib->location,
        'model' => $defib->model,
        'owner' => $defib->owner,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('defibs', ['id' => $defib->id, 'name' => 'New Name']);
});

test('can delete a defib', function (): void {
    $defib = Defib::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.defibs.destroy', $defib));

    $response->assertRedirect(route('admin.defibs.index'));
    $this->assertSoftDeleted('defibs', ['id' => $defib->id]);
});

test('can restore a deleted defib', function (): void {
    $defib = Defib::factory()->create(['deleted_at' => now()]);

    $response = $this->actingAs($this->user)->patch(route('admin.defibs.restore', $defib->id));

    $response->assertRedirect(route('admin.defibs.index'));
    $this->assertDatabaseHas('defibs', ['id' => $defib->id, 'deleted_at' => null]);
});
