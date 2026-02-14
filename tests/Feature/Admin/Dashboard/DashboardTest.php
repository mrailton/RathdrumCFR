<?php

declare(strict_types=1);

use App\Models\Callout;
use App\Models\Defib;
use App\Models\Member;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('dashboard requires authentication', function (): void {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('dashboard displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertViewIs('admin.dashboard.index');
});

test('dashboard shows member statistics', function (): void {
    Member::factory()->count(5)->create(['status' => 'active']);
    Member::factory()->count(3)->create(['status' => 'inactive']);

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('8'); // total members
    $response->assertSee('5'); // active members
    $response->assertSee('3'); // inactive members
});

test('dashboard shows callout statistics', function (): void {
    Callout::factory()->count(10)->create([
        'incident_date' => now(),
        'attended' => true,
    ]);
    Callout::factory()->count(3)->create([
        'incident_date' => now(),
        'attended' => true,
        'ohca_at_scene' => true,
    ]);

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('13'); // total callouts this year
    $response->assertSee('3'); // OHCA callouts
});

test('dashboard shows defib statistics', function (): void {
    Defib::factory()->count(5)->create();
    Defib::factory()->count(2)->create([
        'battery_expires_at' => now()->addMonth(),
    ]);

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('7'); // total defibs
    $response->assertSee('2'); // needs attention
});

test('dashboard shows recent callouts', function (): void {
    $callout = Callout::factory()->create([
        'incident_number' => 'CAD123',
        'incident_date' => now(),
    ]);

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('CAD123');
});
