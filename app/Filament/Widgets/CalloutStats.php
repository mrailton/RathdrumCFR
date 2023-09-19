<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Callout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function now;

class CalloutStats extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $calls = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));

        return [
            Stat::make('Total Received calls', $calls
                ->count()),
            Stat::make('Total Mobilised calls', $calls
                ->clone()
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Total Attended calls', $calls
                ->clone()
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Total Calls To Medical Facilities', $calls
                ->clone()
                ->where('medical_facility', '=', true)
                ->count()),
        ];
    }
}
