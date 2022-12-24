<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs\Reports;

use App\Jobs\Reports\GenerateDefibPadExpiryReport;
use App\Mail\Reports\DefibPadExpiryMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DefibPadExpiryReportTest extends TestCase
{
    /** @test */
    public function sends_the_defib_expiry_report_to_users_that_want_to_receive_reports(): void
    {
        Mail::fake();

        User::factory(['receive_reports' => true])->count(2)->create();
        User::factory(['receive_reports' => false])->count(2)->create();
        Defib::factory()->count(10)->create();

        $this->artisan('reports:defib-pad-expiry');

        Mail::assertQueued(DefibPadExpiryMail::class);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_defibs_that_have_pads_expiring_in_the_next_month(): void
    {
        Defib::factory()->create(['pads_expire_at' => now()->addDays(3)]);
        $defibs = (new GenerateDefibPadExpiryReport())->getDefibs();

        $mailable = new DefibPadExpiryMail($defibs);

        $mailable->assertSeeInHtml($defibs[0]->name);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_no_defibs_that_have_pads_expiring_in_the_next_month(): void
    {
        Defib::factory()->create(['pads_expire_at' => now()->addMonths(3)]);
        $defibs = (new GenerateDefibPadExpiryReport())->getDefibs();

        $mailable = new DefibPadExpiryMail($defibs);

        $mailable->assertSeeInHtml('There are currently no defibs with a expiring or expired pads.');
    }
}
