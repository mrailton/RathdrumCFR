<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateCertExpiryReport;
use App\Mail\Reports\CertExpiryMail;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\artisan;

it('sends the cert expiry report to users that want to receive reports', function () {
    Mail::fake();

    User::factory(['receive_reports' => true])->count(2)->create();
    User::factory(['receive_reports' => false])->count(2)->create();
    Member::factory()->count(10)->create();

    artisan('reports:cert-expiry');

    Mail::assertQueued(CertExpiryMail::class);
});

test('email content renders properly if there are members with a cert expiring in the next 2 months', function () {
    Member::factory()->create(['cfr_certificate_expiry' => now()]);
    $members = (new GenerateCertExpiryReport())->getMembers();

    $mailable = new CertExpiryMail($members);

    $mailable->assertSeeInHtml($members[0]->name);
});

test('email content renders properly if there are no members with an expiring cert in the next 2 months', function () {
    Member::factory()->create(['cfr_certificate_expiry' => now()->addMonths(6)]);
    $members = (new GenerateCertExpiryReport())->getMembers();

    $mailable = new CertExpiryMail($members);

    $mailable->assertSeeInHtml('There are currently no members with expiring CFR certs.');
});
