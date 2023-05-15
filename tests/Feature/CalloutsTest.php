<?php

declare(strict_types=1);

use App\Models\Callout;

test('an authorised user can log a callout that was attended')->todo();



test('an authorised user can log a callout that was not attended')->todo();

test('an authorised user can view a list of callouts', function () {
    authenticatedUser(['callout.list']);
    $callouts = Callout::factory()->count(5)->create();

    $this->get(route('callouts.list'))
        ->assertSee($callouts[0]->ampds_code)
        ->assertSee($callouts[2]->incident_number)
        ->assertSee($callouts[4]->incident_date);
});

test('an authorised user can view a callout', function () {
    authenticatedUser(['callout.view']);
    $callout = Callout::factory()->create();

    $this->get(route('callouts.show', ['callout' => $callout]))
        ->assertSee($callout->ampds_code)
        ->assertSee($callout->incident_number);
});
