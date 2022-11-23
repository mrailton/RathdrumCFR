<?php

declare(strict_types=1);

use App\Models\Defib;

test('a user with permission can view a list of defibs', function () {
    $defibs = Defib::factory()->count(5)->create();

    authenticatedUser(['defib.list'])
        ->get(route('defibs.list'))
        ->assertStatus(200)
        ->assertSee($defibs[0]->name)
        ->assertSee($defibs[1]->name);
});
