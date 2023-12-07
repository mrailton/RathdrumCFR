<?php

namespace App\Helpers;

use App\Models\AMPDSCode;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class WidgetHelper
{
    public function getCalloutWidget(Builder $calls): array
    {
        $cardiacArrestCodes = AMPDSCode::query()->where('arrest_code', '=', true)->pluck('code');

        return [
            Stat::make('Total Received Calls', $calls
                ->count()),
            Stat::make('Total Mobilised Calls', $calls
                ->clone()
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Total Attended Calls', $calls
                ->clone()
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Total Missed Calls', $calls
                ->clone()
                ->where('mobilised', '=', false)
                ->where('attended', '=', false)
                ->where('medical_facility', '=', false)
                ->count()),
            Stat::make('Total Calls To Medical Facilities', $calls
                ->clone()
                ->where('medical_facility', '=', true)
                ->count()),
            Stat::make('Total Cardiac Arrests', $calls
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->count()),
            Stat::make('Total Mobilised Cardiac Arrests', $calls
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Total Attended Cardiac Arrests', $calls
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('attended', '=', true)
                ->count()),
            Stat::make('Total ROSCs Achieved', $calls
                ->clone()
                ->whereIn('ampds_code', $cardiacArrestCodes)
                ->where('rosc_achieved', '=', true)
                ->count()),
        ];

    }
}
