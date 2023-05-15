<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AedSource;
use App\Enums\WasteDisposalMethods;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalloutFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
            'incident_number' => $this->faker->randomNumber(9),
            'incident_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'ampds_code' => $this->faker->randomElement(['10D02', '09E01', '10C02', '11D01']),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => $this->faker->randomNumber(2),
            'notes' => $this->faker->sentence(),
        ];
    }

    public function attended(bool $attended): static
    {
        return $this->state(function (array $attributes) use ($attended) {
            if ($attended) {
                return [
                    'attended' => 'Yes',
                    'ohca_at_scene' => $this->faker->randomElement(['Yes', 'No', 'Unknown']),
                    'bystander_cpr' => $this->faker->randomElement(['Yes', 'No', 'Unknown']),
                    'source_of_aed' => $this->faker->randomElement(AedSource::toArray()),
                    'number_of_shocks_given' => $this->faker->randomNumber(1),
                    'rosc_achieved' => $this->faker->randomElement(['Yes', 'No', 'Unknown']),
                    'patient_transported' => $this->faker->randomElement(['Yes', 'No', 'Unknown']),
                    'responders_at_scene' => $this->faker->numberBetween(1, 4),
                    'ppe_kits_used' => $this->faker->numberBetween(1, 4),
                    'waste_disposal' => $this->faker->randomElement(WasteDisposalMethods::toArray()),
                ];
            }

            return [
                'attended' => 'No',
            ];
        });
    }
}
