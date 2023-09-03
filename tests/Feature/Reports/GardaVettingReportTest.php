<?php

declare(strict_types=1);

use App\Jobs\Reports\GenerateGardaVettingExpiryReport;
use App\Mail\Reports\GardaVettingExpiryMail;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    authenticatedUser();
});

test('it sends the garda vetting expiry report to users that want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['garda_vetting_expiry' => true]);
    Member::factory()->count(10)->create();

    $this->artisan('reports:garda-vetting-expiry');

    Mail::assertQueued(GardaVettingExpiryMail::class);
});

test('it does not send the garda vetting expiry report to users that do not want to receive it', function () {
    Mail::fake();
    User::factory()->create()->reports()->create(['garda_vetting_expiry' => false]);
    Member::factory()->count(10)->create();

    $this->artisan('reports:garda-vetting-expiry');

    Mail::assertNotQueued(GardaVettingExpiryMail::class);
});

test('email content renders properly if there are members with garda vetting expiring in the next 2 months', function () {
    Member::factory()->create(['garda_vetting_date' => now()->subYears(3)]);
    $members = (new GenerateGardaVettingExpiryReport())->getMembers();

    (new GardaVettingExpiryMail($members))
        ->assertSeeInHtml($members[0]->name);
});

test('email content renders properly if there are no members with garda vetting expiring in the next 2 months', function () {
    Member::factory()->create(['garda_vetting_date' => now()]);
    $members = (new GenerateGardaVettingExpiryReport())->getMembers();

    (new GardaVettingExpiryMail($members))
        ->assertSeeInHtml('There are currently no members with expiring Garda Vetting.');
});
