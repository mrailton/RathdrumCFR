<?php

namespace App\Filament\Resources\DefibResource\RelationManagers;

use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InspectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inspections';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Select::make('member_id')
                    ->required()
                    ->options(Member::query()->get()->pluck('name', 'id')->toArray())
                    ->label('Inspected By'),
                DatePicker::make('inspected_at')
                    ->label('Inspection Date')
                    ->required()
                    ->default(now()),
                DatePicker::make('pads_expire_at')
                    ->label('Pads Expiry')
                    ->required(),
                DatePicker::make('battery_expires_at')
                    ->required()
                    ->label('Battery Expiry'),
                TextInput::make('battery_condition')
                    ->required(),
                Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inspected_at')
            ->columns([
                TextColumn::make('member.name'),
                TextColumn::make('inspected_at')
                    ->date()
                    ->label('Inspection Date'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->label('Add Inspection'),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordTitle('Inspection');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
