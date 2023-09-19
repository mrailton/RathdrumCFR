<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as DashboardAlias;

class Dashboard extends DashboardAlias
{
    public function getTitle(): string
    {
        $year = now()->format('Y');

        return "Rathdrum CFR Stats ({$year})";
    }
}
