<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\PermissionsSeeder as Seeder;
use Illuminate\Console\Command;

class PermissionsSeeder extends Command
{
    protected $signature = 'permission:seed';

    protected $description = 'Seeds permissions to database';

    public function handle(): int
    {
        (new Seeder())->run();

        return 0;
    }
}
