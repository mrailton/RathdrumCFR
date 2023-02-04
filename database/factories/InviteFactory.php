<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InviteFactory extends Factory
{
    public function definition(): array
    {
        $email = $this->faker->email();

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'token' => substr(md5(rand(0, 9) . $email . now()->getPreciseTimestamp(3)), 0, 32),
            'expires_at' => now()->addHours(48),
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
        ];
    }
}
