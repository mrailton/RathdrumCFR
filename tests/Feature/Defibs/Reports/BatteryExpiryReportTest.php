<?php

declare(strict_types=1);

namespace Tests\Feature\Defibs\Reports;

use App\Jobs\Reports\GenerateBatteryExpiryReport;
use App\Mail\Reports\BatteryExpiryMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BatteryExpiryReportTest extends TestCase
{
    /** @test */
    public function sends_the_battery_expiry_report_to_users_that_want_to_receive_reports(): void
    {
        Mail::fake();
        User::factory(['receive_reports' => true])->count(2)->create();
        User::factory(['receive_reports' => false])->count(2)->create();
        Defib::factory()->count(10)->create();

        $this->artisan('reports:battery-expiry');

        Mail::assertQueued(BatteryExpiryMail::class);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_defibs_with_expiring_batteries(): void
    {
        Defib::factory()->create(['battery_expires_at' => now()->subMonths(3)]);
        $defibs = (new GenerateBatteryExpiryReport())->getDefibs();

        $mailable = new BatteryExpiryMail($defibs);

        $mailable->assertSeeInHtml($defibs[0]->name);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_no_defibs_with_expiring_batteries(): void
    {
        Defib::factory()->create(['battery_expires_at' => now()->addYears(3)]);
        $defibs = (new GenerateBatteryExpiryReport())->getDefibs();

        $mailable = new BatteryExpiryMail($defibs);

        $mailable->assertSeeInHtml('There are currently no defibs with an expiring or expired battery.');
    }
}
