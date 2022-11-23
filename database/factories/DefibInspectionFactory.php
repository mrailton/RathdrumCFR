<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Defib;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefibInspectionFactory extends Factory
{
    public function definition(): array
    {
        $defib = Defib::count() > 0 ? Defib::get()->random() : Defib::factory()->create();
        $member = Member::count() > 0 ? Member::get()->random() : Member::factory()->create();

        return [
            'user_id' => User::count() > 0 ? User::get()->random() : User::factory()->create(),
            'defib_id' => $defib->id,
            'member_id' => $member->id,
            'inspected_at' => $this->faker->dateTimeThisMonth(),
            'pads_expire_at' => $this->faker->dateTimeInInterval('+6 months'),
            'battery_expires_at' => $this->faker->dateTimeInInterval('+2 years'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
