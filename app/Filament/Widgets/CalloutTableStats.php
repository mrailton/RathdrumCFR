<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\CalloutResource\Pages\ListCallouts;
use App\Helpers\WidgetHelper;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CalloutTableStats extends BaseWidget
{
    use InteractsWithPageTable;
    use HasWidgetShield;

    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListCallouts::class;
    }

    protected function getStats(): array
    {
        $calls = $this->getPageTableQuery();

        return (new WidgetHelper())->getCalloutWidget($calls);
    }
}
