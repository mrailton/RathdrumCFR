<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\AMPDSCode;
use App\Models\Callout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function now;

class FARCallStats extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $farCodes = AMPDSCode::query()->where('far_code', '=', true)->pluck('code');
        $callouts = Callout::query()->whereYear('incident_date', '=', now()->format('Y'));

        return [
            Stat::make('Total Received FAR Calls', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->count()),
            Stat::make('Total Mobilised FAR Calls', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->where('mobilised', '=', true)
                ->count()),
            Stat::make('Total Attended FAR Calls', $callouts
                ->clone()
                ->whereIn('ampds_code', $farCodes)
                ->where('attended', '=', true)
                ->count()),
        ];
    }
}
