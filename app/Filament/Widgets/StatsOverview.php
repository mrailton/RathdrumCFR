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
        $cardiacArrestCodes = ['09E01', '30D01',];

        $callouts = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));
        $arrests = Callout::query()->whereYear('incident_date', '=', now()->format('Y'))->whereIn('ampds_code', $cardiacArrestCodes);
        $defibs = Defib::query();

        return [
            Stat::make('Total Members', Member::query()->count()),
            Stat::make('Active Members', Member::query()->where('status', '=', 'active')->count()),
            Stat::make('Callouts This Year', $callouts->count()),
            Stat::make('Attended Callouts This Year', $callouts->where('attended', '=', 'Yes')->count()),
            Stat::make('Cardiac Arrests This Year', $arrests->count()),
            Stat::make('Attended Cardiac Arrests This Year', $arrests->where('attended', '=', 'Yes')->count()),
            Stat::make('Total Defibrillators', $defibs->count()),
            Stat::make('Public Access Defibrillators', $defibs->where('display_on_map', '=', 1)->count())
        ];
    }
}
