<?php

namespace App\Filament\Resources\DefibResource\Pages;

use App\Filament\Resources\DefibResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDefib extends ViewRecord
{
    protected static string $resource = DefibResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
