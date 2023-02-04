<?php

declare(strict_types=1);

use App\Models\Callout;

test('an authorised user can view a list of callouts', function () {
    authenticatedUser(['callout.list']);
    $callouts = Callout::factory()->count(5)->create();

    $this->get(route('callouts.list'))
        ->assertSee($callouts[0]->ampds_code)
        ->assertSee($callouts[2]->incident_number)
        ->assertSee($callouts[4]->incident_date);
});
