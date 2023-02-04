<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateDefibInspectionReport;
use App\Mail\Reports\DefibInspectionMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('sends the defib inspection report to users that want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_inspection' => true]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:defib-inspection');

    Mail::assertQueued(DefibInspectionMail::class);
});

test('does not sent the defib inspection report to users that do not want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['defib_inspection' => false]);
    Defib::factory()->count(10)->create();

    $this->artisan('reports:defib-inspection');

    Mail::assertNotQueued(DefibInspectionMail::class);
});

test('email content renders properly if there are defibs that have not been inspected in the last month', function () {
    Defib::factory()->create(['last_inspected_at' => now()->subMonths(3)]);
    $defibs = (new GenerateDefibInspectionReport())->getDefibs();

    $mailable = new DefibInspectionMail($defibs);

    $mailable->assertSeeInHtml($defibs[0]->name);
});

test('email content renders properly if there are no defibs that have not been inspected in the last month', function () {
    Defib::factory()->create(['last_inspected_at' => now()->subDays(3)]);
    $defibs = (new GenerateDefibInspectionReport())->getDefibs();

    $mailable = new DefibInspectionMail($defibs);

    $mailable->assertSeeInHtml('All defibs have been inspected with the last month, good job!');
});
