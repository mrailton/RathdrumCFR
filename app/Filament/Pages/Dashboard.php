<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\CalloutStats;
use App\Filament\Widgets\DefibStats;
use App\Filament\Widgets\Filters;
use App\Filament\Widgets\MemberStats;
use Filament\Pages\Dashboard as DashboardAlias;

class Dashboard extends DashboardAlias
{
    public function getWidgets(): array
    {
        return [
            Filters::make(),
            CalloutStats::make(),
            MemberStats::make(),
            DefibStats::make(),
        ];
    }
}
