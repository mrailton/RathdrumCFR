<?php

namespace Tests\Feature\Reports;

use App\Jobs\Reports\GenerateGardaVettingExpiryReport;
use App\Mail\Reports\GardaVettingExpiryMail;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GardaVettingReportTest extends TestCase
{
    /** @test */
    public function it_sends_the_cert_expiry_report_to_users_that_want_to_receive_it(): void
    {
        Mail::fake();
        User::factory()->create()->reports()->create(['garda_vetting_expiry' => true]);
        Member::factory()->count(10)->create();

        $this->artisan('reports:garda-vetting-expiry');

        Mail::assertQueued(GardaVettingExpiryMail::class);
    }

    /** @test */
    public function it_does_not_send_the_cert_expiry_report_to_users_that_do_not_want_to_receive_it(): void
    {
        Mail::fake();
        User::factory()->create()->reports()->create(['garda_vetting_expiry' => false]);
        Member::factory()->count(10)->create();

        $this->artisan('reports:garda-vetting-expiry');

        Mail::assertNotQueued(GardaVettingExpiryMail::class);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_members_with_garda_vetting_expiring_in_the_next_2_month(): void
    {
        Member::factory()->create(['garda_vetting_date' => now()->subYears(3)]);
        $members = (new GenerateGardaVettingExpiryReport())->getMembers();

        (new GardaVettingExpiryMail($members))
            ->assertSeeInHtml($members[0]->name);
    }

    /** @test */
    public function email_content_renders_proper_if_there_are_no_members_with_garda_vetting_expiring_in_the_next_2_months(): void
    {
        Member::factory()->create(['garda_vetting_date' => now()]);
        $members = (new GenerateGardaVettingExpiryReport())->getMembers();

        (new GardaVettingExpiryMail($members))
            ->assertSeeInHtml('There are currently no members with expiring Garda Vetting.');
    }
}
