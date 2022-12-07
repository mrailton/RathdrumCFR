<?php

declare(strict_types=1);

namespace App\Console\Commands\Reports;

use App\Jobs\Reports\GenerateDefibInspectionReport;
use Illuminate\Console\Command;

class DefibInspection extends Command
{
    protected $signature = 'reports:defib-inspection';

    protected $description = 'Generate the defib inspection report';

    public function handle(): int
    {
        GenerateDefibInspectionReport::dispatch();

        return 0;
    }
}
