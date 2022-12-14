<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs;

use App\Models\Defib;
use Tests\TestCase;

class UpdateDefibTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_update_a_defib(): void
    {
        $defib = Defib::factory()->create();
        $data = $defib->toArray();
        $data['name'] = 'Updated Defib';
        $this->actingAs($this->user(['defib.update', 'defib.view']));

        $this->get(route('defibs.view', ['id' => $defib->id]))
            ->assertSee('Update Defib');

        $this->get(route('defibs.edit', ['id' => $defib->id]))
            ->assertSee($defib->name);

        $this->put(route('defibs.update', ['id' => $defib->id]), $data)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('defibs.view', ['id' => $defib->id]);

        $this->get(route('defibs.edit', ['id' => $defib->id]))
            ->assertSee($data['name']);

        $this->assertEquals($data['name'], $defib->fresh()->name);
    }
}
