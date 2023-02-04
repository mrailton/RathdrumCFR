<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateBatteryExpiryReport;
use App\Mail\Reports\BatteryExpiryMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('sends the battery expiry report to users that want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_battery_expiry' => true]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:battery-expiry');

    Mail::assertQueued(BatteryExpiryMail::class);
});

test('does not send the battery expiry report to users that do not want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_battery_expiry' => false]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:battery-expiry');

    Mail::assertNotQueued(BatteryExpiryMail::class);
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
