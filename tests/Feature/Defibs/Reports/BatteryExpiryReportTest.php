<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Defib;
use App\Mail\Reports\BatteryExpiryMail;

use App\Jobs\Reports\GenerateBatteryExpiryReport;

use function Pest\Laravel\artisan;

it('sends the battery expiry report to users that want to receive reports', function () {
    Mail::fake();

    User::factory(['receive_reports' => true])->count(2)->create();
    User::factory(['receive_reports' => false])->count(2)->create();
    Defib::factory()->count(10)->create();

    artisan('reports:battery-expiry');

    Mail::assertSent(BatteryExpiryMail::class);
});

test('email content renders properly if there are defibs with expiring batteries', function () {
    Defib::factory()->create(['battery_expires_at' => now()->subMonths(3)]);
    $defibs = (new GenerateBatteryExpiryReport())->getDefibs();

    $mailable = new BatteryExpiryMail($defibs);

    $mailable->assertSeeInHtml($defibs[0]->name);
});

test('email content renders properly if there are no defibs with expiring batteries', function () {
    Defib::factory()->create(['battery_expires_at' => now()->addYears(3)]);
    $defibs = (new GenerateBatteryExpiryReport())->getDefibs();

    $mailable = new BatteryExpiryMail($defibs);

    $mailable->assertSeeInHtml('There are currently no defibs with an expiring or expired battery.');
});
