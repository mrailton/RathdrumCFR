<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('permission:seed');
    }

    public function user(?array $permissions = null): User
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

        return $user;
    }
}
