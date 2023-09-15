<?php

namespace App\Filament\Widgets;

use App\Models\AMPDSCode;
use App\Models\Callout;
use App\Models\Defib;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $cardiacArrestCodes = AMPDSCode::query()->where('arrest_code', '=', true)->pluck('code');
        $farCodes = AMPDSCode::query()->where('far_code', '=', true)->pluck('code');
        $callouts = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));
        $defibs = Defib::query();

        return [
            Stat::make('Total Members', Member::query()
                ->count()),
            Stat::make('Active Members', Member::query()
                ->where('status', '=', true)
                ->count()),
            Stat::make('Total Defibrillators', $defibs
                ->count()),
            Stat::make('Public Access Defibrillators', $defibs
                ->clone()
                ->where('display_on_map', '=', true)
                ->count()),
            Stat::make('Callouts This Year', $callouts
                ->count()),
            Stat::make('Mobilised Callouts This Year', $callouts
                ->clone()
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Attended Callouts This Year', $callouts
                ->clone()
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Calls To Medical Facilities This Year', $callouts
                ->clone()
                ->where('medical_facility', '=', true)
                ->count()),
            Stat::make('Received FAR Calls This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->count()),
            Stat::make('Mobilised FAR Calls This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Attended FAR Calls This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Cardiac Arrests This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->count()),
            Stat::make('Mobilised Cardiac Arrests This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Attended Cardiac Arrests This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('attended', '=', true)
                ->count()),
            Stat::make('ROSCs Achieved This Year', $callouts
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('rosc_achieved', '=', true)
                ->count()),
        ];
    }
}
