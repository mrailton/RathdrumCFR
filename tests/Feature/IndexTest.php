<?php

declare(strict_types=1);

test('index page loads', function (): void {
    $this->get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('We are currently working on a new website')
        ->assertSee('but in the mean time you can view a map of our available defibrillators in the Rathdrum area')
        ->assertSee('Login');
});
