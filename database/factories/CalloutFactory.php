<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalloutFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'incident_number' => 'CAD' . $this->faker->randomNumber(6),
            'incident_date' => $this->faker->dateTimeThisMonth(),
            'ampds_code' => $this->faker->randomElement(['10D02', '09E01', '10C02', '11D01']),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Unknown']),
            'age' => $this->faker->numberBetween(1, 99),
            'mobilised' => $this->faker->boolean(),
            'attended' => $this->faker->boolean(),
            'notes' => $this->faker->sentence(),
        ];
    }

    public function attended(): static
    {
        return $this->state(fn (array $attributes) => [
            'mobilised' => true,
            'attended' => true,
        ]);
    }

    public function ohca(): static
    {
        return $this->state(fn (array $attributes) => [
            'mobilised' => true,
            'attended' => true,
            'ohca_at_scene' => true,
            'bystander_cpr' => $this->faker->boolean(),
            'source_of_aed' => $this->faker->randomElement(['PAD', 'CFR', 'NAS', 'Fire', 'Garda', 'Other']),
            'number_of_shocks_given' => $this->faker->numberBetween(0, 5),
            'rosc_achieved' => $this->faker->boolean(),
        ]);
    }
}
