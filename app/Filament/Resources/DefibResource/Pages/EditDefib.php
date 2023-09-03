<?php

namespace App\Filament\Resources\DefibResource\Pages;

use App\Filament\Resources\DefibResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefib extends EditRecord
{
    protected static string $resource = DefibResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
