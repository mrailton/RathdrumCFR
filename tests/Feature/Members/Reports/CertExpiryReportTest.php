<?php

declare(strict_types=1);

namespace Tests\Feature\Members\Reports;

use App\Jobs\Reports\GenerateCertExpiryReport;
use App\Mail\Reports\CertExpiryMail;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CertExpiryReportTest extends TestCase
{
    /** @test */
    public function sends_the_cert_expiry_report_to_users_that_want_to_receive_reports(): void
    {
        Mail::fake();

        User::factory(['receive_reports' => true])->count(2)->create();
        User::factory(['receive_reports' => false])->count(2)->create();
        Member::factory()->count(10)->create();

        $this->artisan('reports:cert-expiry');

        Mail::assertQueued(CertExpiryMail::class);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_members_with_a_cert_expiring_in_the_next_2_months(): void
    {
        Member::factory()->create(['cfr_certificate_expiry' => now()]);
        $members = (new GenerateCertExpiryReport())->getMembers();

        (new CertExpiryMail($members))
            ->assertSeeInHtml($members[0]->name);
    }

    /** @test */
    public function email_content_renders_properly_if_there_are_no_members_with_a_cert_expiring_in_the_next_2_months(): void
    {
        Member::factory()->create(['cfr_certificate_expiry' => now()->addMonths(6)]);
        $members = (new GenerateCertExpiryReport())->getMembers();

        (new CertExpiryMail($members))
            ->assertSeeInHtml('There are currently no members with expiring CFR certs.');
    }
}
