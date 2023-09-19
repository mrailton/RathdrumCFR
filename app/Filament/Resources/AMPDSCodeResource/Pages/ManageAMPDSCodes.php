<?php

declare(strict_types=1);

namespace App\Filament\Resources\AMPDSCodeResource\Pages;

use App\Filament\Resources\AMPDSCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAMPDSCodes extends ManageRecords
{
    protected static string $resource = AMPDSCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
