<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs;

use App\Models\Defib;
use Tests\TestCase;

class AddDefibNoteTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_create_a_new_defib_note(): void
    {
        $defib = Defib::factory()->create();
        $this->actingAs($this->user(['defib.view', 'defib.note', 'defib.list']));

        $noteData = [
            'note' => fake()->sentence(),
        ];

        $this->get(route('defibs.view', ['id' => $defib->id]))
            ->assertSee('Add Note');

        $this->get(route('defibs.notes.create', ['id' => $defib->id]))
            ->assertSee('Add Defib Note')
            ->assertSee('Add Note');

        $this->post(route('defibs.notes.store', ['id' => $defib->id]), $noteData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('defibs.view', ['id' => $defib->id]);

        $this->get(route('defibs.view', ['id' => $defib->id]))
            ->assertSee($noteData['note'])
            ->assertSee(auth()->user()->name);
    }
}
