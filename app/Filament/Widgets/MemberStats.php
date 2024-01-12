<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Member;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use function now;

class MemberStats extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $members = Member::query();

        return [
            Stat::make('Total Members', $members
                ->count()),
            Stat::make('Active Members', $members
                ->clone()
                ->where('status', '=', 'active')
                ->count()),
            Stat::make('Members With Cert Expiring', $members
                ->clone()
                ->where('cfr_certificate_expiry', '<', now()->subMonths(3))
                ->count()),
            Stat::make('Members With Vetting Expiring', $members
                ->clone()
                ->where('garda_vetting_date', '<', now()->subYears(3)->addMonths(3))
                ->count()),
        ];
    }
}
