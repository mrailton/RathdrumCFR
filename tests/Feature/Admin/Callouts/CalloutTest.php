<?php

declare(strict_types=1);

use App\Models\Callout;
use App\Models\Member;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('callouts index requires authentication', function (): void {
    $response = $this->get(route('admin.callouts.index'));

    $response->assertRedirect(route('login'));
});

test('callouts index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.callouts.index'));

    $response->assertOk();
    $response->assertViewIs('admin.callouts.index');
});

test('callouts index shows all callouts', function (): void {
    Callout::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.callouts.index'));

    $response->assertOk();
    $response->assertViewHas('callouts', fn ($callouts) => 3 === $callouts->count());
});

test('callouts index can filter by attended status', function (): void {
    Callout::factory()->create(['incident_number' => 'CAD-ATTENDED', 'attended' => true]);
    Callout::factory()->create(['incident_number' => 'CAD-NOT-ATTENDED', 'attended' => false]);

    $response = $this->actingAs($this->user)->get(route('admin.callouts.index', ['attended' => '1']));

    $response->assertOk();
    $response->assertSee('CAD-ATTENDED');
    $response->assertDontSee('CAD-NOT-ATTENDED');
});

test('callouts index can filter by date range', function (): void {
    Callout::factory()->create([
        'incident_number' => 'CAD-IN-RANGE',
        'incident_date' => '2024-06-15',
    ]);
    Callout::factory()->create([
        'incident_number' => 'CAD-OUT-RANGE',
        'incident_date' => '2024-01-15',
    ]);

    $response = $this->actingAs($this->user)->get(route('admin.callouts.index', [
        'from' => '2024-06-01',
        'to' => '2024-06-30',
    ]));

    $response->assertOk();
    $response->assertSee('CAD-IN-RANGE');
    $response->assertDontSee('CAD-OUT-RANGE');
});

test('can view create callout page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.callouts.create'));

    $response->assertOk();
    $response->assertViewIs('admin.callouts.create');
});

test('can create a new callout with required fields', function (): void {
    $calloutData = [
        'incident_number' => 'CAD123456',
        'incident_date' => '2024-06-15 14:30:00',
        'ampds_code' => '10D02',
        'age' => 45,
        'gender' => 'Male',
        'mobilised' => true,
        'attended' => true,
        'notes' => 'Test callout notes',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.callouts.store'), $calloutData);

    $response->assertRedirect();
    $this->assertDatabaseHas('callouts', ['incident_number' => 'CAD123456']);
});

test('callout creation validates required fields', function (): void {
    $calloutData = [
        'notes' => 'Only notes provided',
    ];

    $response = $this->actingAs($this->user)->post(route('admin.callouts.store'), $calloutData);

    $response->assertSessionHasErrors(['incident_number', 'incident_date', 'ampds_code', 'age', 'gender', 'mobilised']);
});

test('can view a callout', function (): void {
    $callout = Callout::factory()->create(['incident_number' => 'CAD-VIEW-TEST']);

    $response = $this->actingAs($this->user)->get(route('admin.callouts.show', $callout));

    $response->assertOk();
    $response->assertViewIs('admin.callouts.show');
    $response->assertSee('CAD-VIEW-TEST');
});

test('can view edit callout page', function (): void {
    $callout = Callout::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.callouts.edit', $callout));

    $response->assertOk();
    $response->assertViewIs('admin.callouts.edit');
});

test('can update a callout', function (): void {
    $callout = Callout::factory()->create(['incident_number' => 'OLD-NUMBER']);

    $response = $this->actingAs($this->user)->put(route('admin.callouts.update', $callout), [
        'incident_number' => 'NEW-NUMBER',
        'incident_date' => '2024-06-15 14:30:00',
        'ampds_code' => '10D02',
        'age' => 50,
        'gender' => 'Female',
        'mobilised' => true,
        'attended' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('callouts', ['id' => $callout->id, 'incident_number' => 'NEW-NUMBER']);
});

test('can delete a callout', function (): void {
    $callout = Callout::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.callouts.destroy', $callout));

    $response->assertRedirect(route('admin.callouts.index'));
    $this->assertDatabaseMissing('callouts', ['id' => $callout->id]);
});

test('can attach members to a callout', function (): void {
    $members = Member::factory()->count(2)->create(['status' => 'active']);

    $calloutData = [
        'incident_number' => 'CAD-WITH-MEMBERS',
        'incident_date' => '2024-06-15 14:30:00',
        'ampds_code' => '10D02',
        'age' => 45,
        'gender' => 'Male',
        'mobilised' => true,
        'attended' => true,
        'members' => $members->pluck('id')->toArray(),
    ];

    $response = $this->actingAs($this->user)->post(route('admin.callouts.store'), $calloutData);

    $response->assertRedirect();
    $callout = Callout::where('incident_number', 'CAD-WITH-MEMBERS')->first();
    expect($callout->members)->toHaveCount(2);
});
