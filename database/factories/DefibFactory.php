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
            'user_id' => User::factory(),
            'name' => $this->faker->streetName() . ' AED',
            'location' => $this->faker->streetAddress(),
            'owner' => 'RathdrumCFR',
            'model' => 'iPad CU-SP1',
            'serial' => (string) $this->faker->randomNumber(8),
            'last_inspected_at' => $this->faker->dateTimeThisYear(),
            'last_inspected_by' => $this->faker->name(),
            'pads_expire_at' => $this->faker->dateTimeBetween('now', '+2 years'),
            'battery_expires_at' => $this->faker->dateTimeBetween('now', '+3 years'),
            'last_serviced_at' => $this->faker->dateTimeThisYear(),
            'display_on_map' => $this->faker->boolean(),
            'coordinates' => $this->faker->latitude() . ', ' . $this->faker->longitude(),
        ];
    }

    public function needsAttention(): static
    {
        return $this->state(fn (array $attributes) => [
            'battery_expires_at' => now()->addMonth(),
            'pads_expire_at' => now()->addMonth(),
        ]);
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
