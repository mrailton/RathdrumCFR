<?php

use App\Models\Defib;
use App\Models\Member;

use function Pest\Faker\faker;

test('an authorised user can create a new defib inspection', function () {
    $defib = Defib::factory()->create();
    $member = Member::factory()->create();
    $request = authenticatedUser(['defib.view', 'defib.inspect', 'defib.list']);
    $inspectionDate = faker()->dateTime();
    $padExpiry = faker()->dateTimeBetween('+1 month', '+2 years');
    $batteryExpiry = faker()->dateTimeBetween('+2 month', '+4 years');

    $inspectionData = [
        'member_id' => $member->id,
        'inspected_at' => $inspectionDate->format('Y-m-d H:i:s'),
        'pads_expire_at' => $padExpiry->format('Y-m-d'),
        'battery_expires_at' => $batteryExpiry->format('Y-m-d'),
        'notes' => faker()->sentence(),
    ];

    $request->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee('Add Inspection');

    $request->get(route('defibs.inspections.create', ['id' => $defib->id]))
        ->assertSee('Add Defib Inspection')
        ->assertSee('Add Inspection');

    $request->post(route('defibs.inspections.store', ['id' => $defib->id]), $inspectionData)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('defibs.view', ['id' => $defib->id]);

    $request->get(route('defibs.view', ['id' => $defib->id]))
        ->assertSee($member->name)
        ->assertSee($padExpiry->format('l jS F Y'))
        ->assertSee($batteryExpiry->format('l jS F Y'))
        ->assertSee($inspectionData['notes']);
});
