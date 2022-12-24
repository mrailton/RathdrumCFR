<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs\Reports;

use App\Jobs\Reports\GenerateDefibInspectionReport;
use App\Mail\Reports\DefibInspectionMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DefibInspectionReportTest extends TestCase
{
    /** @test */
    public function send_the_defib_inspection_report_to_users_that_want_to_receive_reports(): void
    {
        Mail::fake();

        User::factory(['receive_reports' => true])->count(2)->create();
        User::factory(['receive_reports' => false])->count(2)->create();
        Defib::factory()->count(10)->create();

        $this->artisan('reports:defib-inspection');

        Mail::assertQueued(DefibInspectionMail::class);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_defibs_that_have_not_been_inspected_in_the_last_month(): void
    {
        Defib::factory()->create(['last_inspected_at' => now()->subMonths(3)]);
        $defibs = (new GenerateDefibInspectionReport())->getDefibs();

        $mailable = new DefibInspectionMail($defibs);

        $mailable->assertSeeInHtml($defibs[0]->name);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_no_defibs_that_have_not_been_inspected_in_the_last_month(): void
    {
        Defib::factory()->create(['last_inspected_at' => now()->subDays(3)]);
        $defibs = (new GenerateDefibInspectionReport())->getDefibs();

        $mailable = new DefibInspectionMail($defibs);

        $mailable->assertSeeInHtml('All defibs have been inspected with the last month, good job!');
    }
}
