<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateCertExpiryReport;
use App\Mail\Reports\CertExpiryMail;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    authenticatedUser();
});

test('sends the cert expiry report to users that want to receive it', function (): void {
    Mail::fake();
    User::factory()->create()->reports()->create(['cfr_cert_expiry' => true]);
    Member::factory()->count(10)->create();

    $this->artisan('reports:cert-expiry');

    Mail::assertQueued(CertExpiryMail::class);
});

test('does not send the cert expiry report to users that do not want to receive it', function (): void {
    Mail::fake();
    User::factory()->create()->reports()->create(['cfr_cert_expiry' => false]);
    Member::factory()->count(10)->create();

    $this->artisan('reports:cert-expiry');

    Mail::assertNotQueued(CertExpiryMail::class);
});

test('email content renders properly if there are members with a cert expiring in the next 2 months', function (): void {
    Member::factory()->create(['cfr_certificate_expiry' => now()]);
    $members = (new GenerateCertExpiryReport())->getMembers();

    (new CertExpiryMail($members))
        ->assertSeeInHtml($members[0]->name);
});

test('email content renders properly if there are no members with a cert expiring in the next 2 months', function (): void {
    Member::factory()->create(['cfr_certificate_expiry' => now()->addMonths(6)]);
    $members = (new GenerateCertExpiryReport())->getMembers();

    (new CertExpiryMail($members))
        ->assertSeeInHtml('There are currently no members with expiring CFR certs.');
});
