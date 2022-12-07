<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\GenerateBatteryExpiryReport;
use Illuminate\Console\Command;

class BatteryExpiry extends Command
{
    protected $signature = 'reports:battery-expiry';

    protected $description = 'Generate the battery expiry report';

    public function handle(): int
    {
        GenerateBatteryExpiryReport::dispatch();

        return 0;
    }
}
