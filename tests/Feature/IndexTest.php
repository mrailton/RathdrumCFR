<?php

declare(strict_types=1);

test('the index loads', function () {
    guest()
        ->get(route('index'))
        ->assertStatus(200)
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('We are currently working on a brand new website.')
        ->assertSee('Login');
});
