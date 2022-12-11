<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Defib;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reports\DefibInspectionMail;

use App\Jobs\Reports\GenerateDefibInspectionReport;

use function Pest\Laravel\artisan;

it('sends the defib inspection report to users that want to receive reports', function () {
    Mail::fake();

    User::factory(['receive_reports' => true])->count(2)->create();
    User::factory(['receive_reports' => false])->count(2)->create();
    Defib::factory()->count(10)->create();

    artisan('reports:defib-inspection');

    Mail::assertQueued(DefibInspectionMail::class);
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
