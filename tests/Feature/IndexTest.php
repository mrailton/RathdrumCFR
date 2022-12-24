<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function index_page_loads(): void
    {
        $this->get(route('index'))
            ->assertStatus(200)
            ->assertSee('Rathdrum Community First Responders')
            ->assertSee('We are currently working on a new website')
            ->assertSee('but in the mean time you can view a map of our available defibrillators in the Rathdrum area')
            ->assertSee('Login');
    }
}
