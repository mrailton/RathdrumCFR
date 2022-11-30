<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Defib;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reports\DefibPadExpiryMail;

use App\Jobs\Reports\GenerateDefibPadExpiryReport;

use function Pest\Laravel\artisan;

it('sends the defib expiry report to users that want to receive reports', function () {
    Mail::fake();

    User::factory(['receive_reports' => true])->count(2)->create();
    User::factory(['receive_reports' => false])->count(2)->create();
    Defib::factory()->count(10)->create();

    artisan('reports:defib-pad-expiry');

    Mail::assertSent(DefibPadExpiryMail::class);
});

test('email content renders properly if there are defibs that pads expiring in the next month', function () {
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
