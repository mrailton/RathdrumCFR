<?php

declare(strict_types=1);

use App\Models\Defib;
use App\Models\Member;

test('an authorised user can create a new defib inspection', function () {
    authenticatedUser(['defib.view', 'defib.inspect', 'defib.list']);
    $defib = Defib::factory()->create();
    $member = Member::factory()->create();
    $inspectionDate = fake()->dateTime();
    $padExpiry = fake()->dateTimeBetween('+1 month', '+2 years');
    $batteryExpiry = fake()->dateTimeBetween('+2 month', '+4 years');

    $inspectionData = [
        'member_id' => $member->id,
        'inspected_at' => $inspectionDate->format('Y-m-d H:i:s'),
        'pads_expire_at' => $padExpiry->format('Y-m-d'),
        'battery_expires_at' => $batteryExpiry->format('Y-m-d'),
        'notes' => fake()->sentence(),
    ];

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee('Add Inspection');

    $this->get(route('defibs.inspections.create', ['defib' => $defib->id]))
        ->assertSee('Add Defib Inspection')
        ->assertSee('Add Inspection');

    $this->post(route('defibs.inspections.store', ['defib' => $defib->id]), $inspectionData)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['defib' => $defib->id]);

    $this->get(route('defibs.view', ['defib' => $defib->id]))
        ->assertSee($member->name)
        ->assertSee($padExpiry->format('l jS F Y'))
        ->assertSee($batteryExpiry->format('l jS F Y'))
        ->assertSee($inspectionData['notes']);
});
