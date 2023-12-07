<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Helpers\WidgetHelper;
use App\Models\AMPDSCode;
use App\Models\Callout;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;
use function now;

class CalloutStats extends BaseWidget
{
    protected static ?int $sort = 2;

    public Carbon $fromDate;
    public Carbon $toDate;

    public function mount(): void
    {
        $this->fromDate = Filters::$public['from'];
        $this->toDate = Filters::$public['to'];
    }

    #[On('updateFromDate')]
    public function updateFromDate(string $from): void
    {
        $this->fromDate = Carbon::make($from);
        $this->getStats();
    }

    #[On('updateToDate')]
    public function updateToDate(string $to): void
    {
        $this->toDate = Carbon::make($to);
        $this->getStats();
    }

    protected function getStats(): array
    {
        $fromDate = $this->fromDate ??= now()->startOfYear();
        $toDate = $this->toDate ??= now()->endOfYear();


        $calls = Callout::query()->whereDate('incident_date', '>=', $fromDate)->whereDate('incident_date', '<=', $toDate);

        return (new WidgetHelper())->getCalloutWidget($calls);
    }
}
