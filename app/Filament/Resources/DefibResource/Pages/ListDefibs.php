<?php

declare(strict_types=1);

namespace App\Filament\Resources\DefibResource\Pages;

use App\Filament\Resources\DefibResource;
use App\Filament\Widgets\DefibStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDefibs extends ListRecords
{
    protected static string $resource = DefibResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DefibStats::class,
        ];
    }
}
