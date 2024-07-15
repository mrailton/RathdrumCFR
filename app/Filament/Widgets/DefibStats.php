<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Defib;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use function now;

class DefibStats extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 2;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $defibs = Defib::query();

        return [

            Stat::make('Total Defibrillators', $defibs
                ->count()),
            Stat::make('Public Access Defibrillators', $defibs
                ->clone()
                ->where('display_on_map', '=', true)
                ->count()),
            Stat::make('Defibs With Expiring Pads', $defibs
                ->clone()
                ->where('pads_expire_at', '<', now()->addMonths(3))
                ->orWhereNull('pads_expire_at')
                ->count()),
            Stat::make('Defibs With Expiring Battery', $defibs
                ->clone()
                ->where('battery_expires_at', '<', now()->addMonths(3))
                ->orWhereNull('pads_expire_at')
                ->count()),
        ];
    }
}
