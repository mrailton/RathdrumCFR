<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('backup:run')->dailyAt('02:30');
        $schedule->command('reports:battery-expiry')->monthlyOn(1, '09:00');
        $schedule->command('reports:cert-expiry')->monthlyOn(1, '09:00');
        $schedule->command('reports:defib-inspection')->sundays()->at('09:00');
        $schedule->command('reports:defib-pad-expiry')->monthlyOn(1, '09:00');
        $schedule->command('reports:garda-vetting-expiry')->monthlyOn(1, '09:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
