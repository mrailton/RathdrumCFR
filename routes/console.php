<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('backup:clean')->dailyAt('03:15');
Schedule::command('backup:run')->dailyAt('03:30');
Schedule::command('reports:cert-expiry')->monthlyOn(1, '09:00');
Schedule::command('reports:battery-expiry')->monthlyOn(1, '09:00');
Schedule::command('reports:defib-inspection')->monthlyOn(1, '09:00');
Schedule::command('reports:defib-pad-expiry')->monthlyOn(1, '09:00');
Schedule::command('reports:garda-vetting-expiry')->monthlyOn(1, '09:00');
