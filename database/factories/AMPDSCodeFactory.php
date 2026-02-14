<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AMPDSCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AMPDSCode>
 */
class AMPDSCodeFactory extends Factory
{
    protected $model = AMPDSCode::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->regexify('[0-9]{1,2}[A-Z]-[0-9]{1,2}'),
            'description' => fake()->sentence(),
            'arrest_code' => fake()->boolean(),
            'far_code' => fake()->boolean(),
        ];
    }
}
