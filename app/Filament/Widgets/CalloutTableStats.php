<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\CalloutResource\Pages\ListCallouts;
use App\Helpers\WidgetHelper;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CalloutTableStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?int $sort = 1;

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
