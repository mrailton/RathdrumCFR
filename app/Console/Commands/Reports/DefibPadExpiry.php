<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\GenerateDefibPadExpiryReport;
use Illuminate\Console\Command;

class DefibPadExpiry extends Command
{
    protected $signature = 'reports:defib-pad-expiry';

    protected $description = 'Generate the defib pad expiry report';

    public function handle(): int
    {
        GenerateDefibPadExpiryReport::dispatch();

        return 0;
    }
}
