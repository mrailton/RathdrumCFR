<?php

declare(strict_types=1);

namespace App\Filament\Resources\CalloutResource\Pages;

use App\Filament\Resources\CalloutResource;
use App\Filament\Widgets\CalloutTableStats;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListCallouts extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = CalloutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Log Callout'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalloutTableStats::make(),
        ];
    }

    public function getColumns(): int|string|array
    {
        return 4;
    }
}
