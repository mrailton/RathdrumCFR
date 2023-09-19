<?php

declare(strict_types=1);

namespace App\Filament\Resources\CalloutResource\Pages;

use App\Filament\Resources\CalloutResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCallout extends ViewRecord
{
    protected static string $resource = CalloutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
