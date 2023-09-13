<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use App\Filament\Resources\CalloutResource;
use App\Models\Callout;
use Filament\Tables\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CalloutsRelationManager extends RelationManager
{
    protected static string $relationship = 'callouts';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('incident_number')
                    ->label('CAD Number'),
                TextColumn::make('incident_date')->date('M j, Y H:i')
                    ->label('Incident Date'),
                TextColumn::make('ampds_code')
                    ->label('AMPDS Code'),
            ])
            ->actions([
                Action::make('view')->url(fn (Callout $record): string => CalloutResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
