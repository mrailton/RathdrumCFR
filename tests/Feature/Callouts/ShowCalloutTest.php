<?php

declare(strict_types=1);

use App\Models\Callout;

test('an authorised user can view a callout', function () {
    authenticatedUser(['callout.view']);
    $callout = Callout::factory()->create();

    $this->get(route('callouts.show', ['callout' => $callout]))
        ->assertSee($callout->ampds_code)
        ->assertSee($callout->incident_number);
});
