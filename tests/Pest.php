<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(Tests\TestCase::class)->in('Feature');
uses(LazilyRefreshDatabase::class)->in('Feature');
uses()->beforeEach(fn () => $this->seed(PermissionsSeeder::class))->in('Feature');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function authenticatedUser(?array $permissions = null): Tests\TestCase
{
    $data = [
        'name' => 'Test User',
        'email' => 'test@user.com',
        'password' => 'password',
    ];

    $user = User::first() ?: User::create($data);

    if ($permissions) {
        foreach ($permissions as $permission) {
            $user->givePermissionTo($permission);
        }
    }

    return test()->actingAs($user);
}

function guest()
{
    return test();
}
