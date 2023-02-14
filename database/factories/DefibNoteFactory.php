<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Defib;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefibNoteFactory extends Factory
{
    public function definition(): array
    {
        $defib = Defib::count() > 0 ? Defib::get()->random() : Defib::factory()->create();

        return [
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
            'defib_id' => $defib->id,
            'note' => $this->faker->sentence(20),
        ];
    }
}
