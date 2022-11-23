<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('the index loads', function () {
    get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('We are currently working on a brand new website.');
});
