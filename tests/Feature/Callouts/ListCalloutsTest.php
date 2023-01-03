<?php

declare(strict_types=1);

namespace Tests\Feature\Callouts;

use App\Models\Callout;
use Tests\TestCase;

class ListCalloutsTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_list_of_callouts(): void
    {
        $this->actingAs($this->user(['callout.list']));
        $callouts = Callout::factory()->count(5)->create();

        $this->get(route('callouts.list'))
            ->assertSee($callouts[0]->ampds_code)
            ->assertSee($callouts[2]->incident_number)
            ->assertSee($callouts[4]->incident_date);
    }
}
