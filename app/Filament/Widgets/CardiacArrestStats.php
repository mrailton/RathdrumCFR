<?php

namespace App\Filament\Widgets;

use App\Models\AMPDSCode;
use App\Models\Callout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use function now;

class CardiacArrestStats extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $cardiacArrestCodes = AMPDSCode::query()->where('arrest_code', '=', true)->pluck('code');
        $callouts = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));

        return [
            Stat::make('Total Cardiac Arrests', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->count()),
            Stat::make('Total Mobilised Cardiac Arrests', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Total Attended Cardiac Arrests', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Total ROSCs Achieved', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('rosc_achieved', '=', true)
                ->count()),
        ];
    }
}
