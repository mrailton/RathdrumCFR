<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\GenerateGardaVettingExpiryReport;
use Illuminate\Console\Command;

class GardaVettingExpiry extends Command
{
    protected $signature = 'reports:garda-vetting-expiry';

    protected $description = 'Generate the garda vetting expiry report';

    public function handle(): int
    {
        GenerateGardaVettingExpiryReport::dispatch();

        return 0;
    }
}
