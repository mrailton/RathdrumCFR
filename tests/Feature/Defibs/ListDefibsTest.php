<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs;

use App\Models\Defib;
use Tests\TestCase;

class ListDefibsTest extends TestCase
{
    /** @test */
    public function a_user_with_permission_can_view_a_list_of_defibs(): void
    {
        $defibs = Defib::factory()->count(5)->create();
        $this->actingAs($this->user(['defib.list']));

        $this->get(route('defibs.list'))
            ->assertStatus(200)
            ->assertSee($defibs[0]->name)
            ->assertSee($defibs[1]->name);
    }

    /** @test */
    public function a_gurst_can_not_view_a_list_of_defibs(): void
    {
        Defib::factory()->count(5)->create();

        $this->get(route('defibs.list'))
            ->assertStatus(302)
            ->assertRedirectToRoute('login.create');
    }

    /** @test */
    public function a_user_without_permission_can_not_view_a_list_of_defibs(): void
    {
        $defibs = Defib::factory()->count(5)->create();

        $this->get(route('defibs.list'))
            ->assertStatus(302)
            ->assertDontSee($defibs[0]->name)
            ->assertDontSee($defibs[1]->name);
    }
}
