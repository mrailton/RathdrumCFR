<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\GenerateCertExpiryReport;
use Illuminate\Console\Command;

class CertExpiry extends Command
{
    protected $signature = 'reports:cert-expiry';

    protected $description = 'Generate the cert expiry report';

    public function handle(): int
    {
        GenerateCertExpiryReport::dispatch();

        return 0;
    }
}
