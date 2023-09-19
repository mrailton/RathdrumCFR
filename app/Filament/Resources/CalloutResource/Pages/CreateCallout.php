<?php

declare(strict_types=1);

namespace App\Filament\Resources\CalloutResource\Pages;

use App\Filament\Resources\CalloutResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCallout extends CreateRecord
{
    protected static string $resource = CalloutResource::class;
    protected static bool $canCreateAnother = true;
}
