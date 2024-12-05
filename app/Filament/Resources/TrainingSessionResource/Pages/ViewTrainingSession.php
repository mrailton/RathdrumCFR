<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrainingSessionResource\Pages;

use App\Filament\Resources\TrainingSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingSession extends ViewRecord
{
    protected static string $resource = TrainingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
