<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateDefibPadExpiryReport;
use App\Mail\Reports\DefibPadExpiryMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    authenticatedUser();
});

test('sends the defibs expiry report to users that want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_pad_expiry' => true]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:defib-pad-expiry');

    Mail::assertQueued(DefibPadExpiryMail::class);
});

test('does not send the defib expiry report to users that do not want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_pad_expiry' => false]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:defib-pad-expiry');

    Mail::assertNotQueued(DefibPadExpiryMail::class);
});

test('email content renders properly if there are defibs that have pads expiring in hte next month', function () {
    Defib::factory()->create(['pads_expire_at' => now()->addDays(3)]);
    $defibs = (new GenerateDefibPadExpiryReport())->getDefibs();

    $mailable = new DefibPadExpiryMail($defibs);

    $mailable->assertSeeInHtml($defibs[0]->name);
});

test('email content renders properly if there are no defibs that have pads expiring in the next month', function () {
    Defib::factory()->create(['pads_expire_at' => now()->addMonths(3)]);
    $defibs = (new GenerateDefibPadExpiryReport())->getDefibs();

    $mailable = new DefibPadExpiryMail($defibs);

    $mailable->assertSeeInHtml('There are currently no defibs with a expiring or expired pads.');
});
