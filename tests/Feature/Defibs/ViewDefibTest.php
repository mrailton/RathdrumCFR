<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs;

use App\Models\Defib;
use Tests\TestCase;

class ViewDefibTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_view_a_defib(): void
    {
        $defib = Defib::factory()->create();
        $this->actingAs($this->user(['defib.view']));

        $this->get(route('defibs.view', ['id' => $defib->id]))
            ->assertSee($defib->name)
            ->assertSee($defib->location);
    }

    /** @test */
    public function an_auhtorised_user_can_not_view_a_defib_that_does_not_exist(): void
    {
        $this->actingAs($this->user(['defib.view']));

        $this->get(route('defibs.view', ['id' => 124343]))
            ->assertStatus(404);
    }
}
