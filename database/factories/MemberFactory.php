<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'address_1' => $this->faker->address(),
            'address_2' => $this->faker->city(),
            'title' => 'Responder',
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
