<?php

declare(strict_types=1);

use App\Models\AMPDSCode;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('ampds codes index requires authentication', function (): void {
    $response = $this->get(route('admin.ampds-codes.index'));

    $response->assertRedirect(route('login'));
});

test('ampds codes index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.index'));

    $response->assertOk();
    $response->assertViewIs('admin.ampds-codes.index');
});

test('ampds codes index shows all codes', function (): void {
    AMPDSCode::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.index'));

    $response->assertOk();
    $response->assertViewHas('ampds_codes', fn ($ampds_codes) => 3 === $ampds_codes->count());
});

test('ampds codes index can search', function (): void {
    AMPDSCode::factory()->create(['code' => '1A-1', 'description' => 'Cardiac Arrest']);
    AMPDSCode::factory()->create(['code' => '2B-2', 'description' => 'Breathing Problems']);

    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.index', ['search' => 'Cardiac']));

    $response->assertOk();
    $response->assertSee('Cardiac Arrest');
    $response->assertDontSee('Breathing Problems');
});

test('can view create ampds code page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.create'));

    $response->assertOk();
    $response->assertViewIs('admin.ampds-codes.create');
});

test('can create a new ampds code', function (): void {
    $ampdsCodeData = [
        'code' => '9E-1',
        'description' => 'Cardiac Emergency',
        'arrest_code' => true,
        'far_code' => false,
    ];

    $response = $this->actingAs($this->user)->post(route('admin.ampds-codes.store'), $ampdsCodeData);

    $response->assertRedirect();
    $this->assertDatabaseHas('ampds_codes', [
        'code' => '9E-1',
        'description' => 'Cardiac Emergency',
        'arrest_code' => true,
        'far_code' => false,
    ]);
});

test('ampds code creation validates required fields', function (): void {
    $response = $this->actingAs($this->user)->post(route('admin.ampds-codes.store'), []);

    $response->assertSessionHasErrors(['code', 'description']);
});

test('can view an ampds code', function (): void {
    $ampdsCode = AMPDSCode::factory()->create(['code' => '9E-1', 'description' => 'Cardiac Emergency']);

    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.show', $ampdsCode));

    $response->assertOk();
    $response->assertViewIs('admin.ampds-codes.show');
    $response->assertSee('9E-1');
});

test('can view edit ampds code page', function (): void {
    $ampdsCode = AMPDSCode::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.ampds-codes.edit', $ampdsCode));

    $response->assertOk();
    $response->assertViewIs('admin.ampds-codes.edit');
});

test('can update an ampds code', function (): void {
    $ampdsCode = AMPDSCode::factory()->create([
        'code' => '1A-1',
        'description' => 'Old Description',
        'arrest_code' => false,
        'far_code' => false,
    ]);

    $response = $this->actingAs($this->user)->put(route('admin.ampds-codes.update', $ampdsCode), [
        'code' => '1A-1',
        'description' => 'New Description',
        'arrest_code' => true,
        'far_code' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('ampds_codes', [
        'id' => $ampdsCode->id,
        'description' => 'New Description',
        'arrest_code' => true,
        'far_code' => true,
    ]);
});

test('can delete an ampds code', function (): void {
    $ampdsCode = AMPDSCode::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.ampds-codes.destroy', $ampdsCode));

    $response->assertRedirect(route('admin.ampds-codes.index'));
    $this->assertDatabaseMissing('ampds_codes', ['id' => $ampdsCode->id]);
});
