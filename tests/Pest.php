<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(Tests\TestCase::class)->in('Feature');
uses(LazilyRefreshDatabase::class)->in('Feature');

function authenticatedUser(): Tests\TestCase
{
    $data = [
        'name' => 'Test User',
        'email' => 'test@user.com',
        'password' => 'password',
    ];

    $user = User::first() ?: User::create($data);

    return test()->actingAs($user);
}

function guest()
{
    return test();
}
