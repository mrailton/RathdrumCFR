<?php

namespace App\Filament\Widgets;

use App\Models\Callout;
use App\Models\Defib;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use function now;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $cardiacArrestCodes = ['09E01', '30D01', '09D01'];

        $callouts = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));
        $defibs = Defib::query();

        return [
            Stat::make('Total Members', Member::query()
                ->count()),
            Stat::make('Active Members', Member::query()
                ->where('status', '=', 'active')
                ->count()),
            Stat::make('Callouts This Year', $callouts
                ->count()),
            Stat::make('Mobilised Callouts This Year', $callouts
                ->clone()
                ->where('mobilised', '=', 'Yes')
                ->count()),
            Stat::make('Attended Callouts This Year', $callouts
                ->clone()
                ->where('attended', '=', 'Yes')
                ->count()),
            Stat::make('Calls To Medical Facilities This Year', $callouts
                ->clone()
                ->where('medical_facility', '=', 'Yes')
                ->count()),
            Stat::make('Cardiac Arrests This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->count()),
            Stat::make('Attended Cardiac Arrests This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('attended', '=', 'Yes')
                ->count()),
            Stat::make('ROSCs Achieved This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('rosc_achieved', '=', 'Yes')
                ->count()),
            Stat::make('Total Defibrillators', $defibs
                ->count()),
            Stat::make('Public Access Defibrillators', $defibs
                ->clone()
                ->where('display_on_map', '=', 1)
                ->count()),
        ];
    }
}
