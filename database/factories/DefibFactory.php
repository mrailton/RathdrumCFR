<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefibFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
            'name' => $this->faker->streetName(),
            'location' => $this->faker->streetAddress(),
            'owner' => $this->faker->name(),
            'model' => 'iPad CU-SP1',
            'serial' => $this->faker->randomNumber(8),
            'last_inspected_at' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'last_inspected_by' => $this->faker->name(),
            'pads_expire_at' => $this->faker->dateTimeThisDecade()->format('Y-m-d'),
            'battery_expires_at' => $this->faker->dateTimeThisDecade()->format('Y-m-d'),
            'last_serviced_at' => $this->faker->dateTimeThisDecade()->format('Y-m-d'),
            'display_on_map' => $this->faker->boolean,
            'coordinates' => $this->faker->latitude . ', ' . $this->faker->longitude,
        ];
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'display_on_map' => true,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'display_on_map' => false,
        ]);
    }
}
