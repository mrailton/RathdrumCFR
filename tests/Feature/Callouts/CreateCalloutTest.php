<?php

declare(strict_types=1);

use App\Models\Callout;


test('an authorised user can log a callout that was attended', function () {
    authenticatedUser(['callout.list', 'callout.create']);
    $callout = Callout::factory()->attended(true)->make();

    $this->assertDatabaseEmpty(Callout::class);

    $this->get(route('callouts.create'))
        ->assertSee('Log Callout');

    $this->post(route('callouts.store', $callout->toArray()))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('callouts.list')
        ->assertSessionHas('success', 'Callout successfully logged');

    $this->assertDatabaseCount(Callout::class, 1);

    $this->get(route('callouts.list'))
        ->assertSee($callout->ampds_code)
        ->assertSee($callout->incident_number)
        ->assertSee($callout->incident_date);
});

test('an authorised user can log a callout that was not attended', function () {
    authenticatedUser(['callout.list', 'callout.create']);
    $callout = Callout::factory()->attended(false)->make();

    $this->assertDatabaseEmpty(Callout::class);

    $this->get(route('callouts.create'))
        ->assertSee('Log Callout');

    $this->post(route('callouts.store', $callout->toArray()))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('callouts.list')
        ->assertSessionHas('success', 'Callout successfully logged');

    $this->assertDatabaseCount(Callout::class, 1);

    $this->get(route('callouts.list'))
        ->assertSee($callout->ampds_code)
        ->assertSee($callout->incident_number)
        ->assertSee($callout->incident_date);
});
