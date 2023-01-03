<?php

declare(strict_types=1);

namespace Tests\Feature\Callouts;

use App\Models\Callout;
use Tests\TestCase;

class CreateCalloutTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_log_a_callout(): void
    {
        $this->actingAs($this->user(['callout.list', 'callout.create']));
        $callout = Callout::factory()->make();

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
    }
}
