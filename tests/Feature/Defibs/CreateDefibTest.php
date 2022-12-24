<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs;

use App\Models\Defib;
use Tests\TestCase;

class CreateDefibTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_add_a_new_defib(): void
    {
        $data = Defib::factory()->make()->toArray();
        $this->actingAs($this->user(['defib.create']));
        $this->assertDatabaseCount(Defib::class, 0);

        $this->get(route('defibs.create'))
            ->assertSee('Add Defib')
            ->assertSee('Battery Expires On');

        $this->post(route('defibs.store'), $data)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('defibs.list');

        $this->assertDatabaseCount(Defib::class, 1);
        $this->assertEquals($data['name'], Defib::first()->name);
    }
}
