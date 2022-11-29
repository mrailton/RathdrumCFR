<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Defib;
use App\Mail\Reports\BatteryExpiryMail;

use function Pest\Laravel\artisan;

it('sends the battery expiry report to users that want to receive reports', function () {
    Mail::fake();

    User::factory(['receive_reports' => true])->count(2)->create();
    User::factory(['receive_reports' => false])->count(2)->create();
    Defib::factory()->count(10)->create();

    artisan('reports:battery-expiry');

    Mail::assertSent(BatteryExpiryMail::class);
});
