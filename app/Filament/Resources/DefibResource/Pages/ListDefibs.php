<?php

namespace App\Filament\Resources\DefibResource\Pages;

use App\Filament\Resources\DefibResource;
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
}
