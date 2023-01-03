<?php

declare(strict_types=1);

namespace Tests\Feature\Callouts;

use App\Models\Callout;
use Tests\TestCase;

class ShowCalloutTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_callout(): void
    {
        $this->actingAs($this->user(['callout.view']));
        $callout = Callout::factory()->create();

        $this->get(route('callouts.show', ['callout' => $callout]))
            ->assertSee($callout->ampds_code)
            ->assertSee($callout->incident_number);
    }
}
