<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AMPDSCodeResource\Pages\ManageAMPDSCodes;
use App\Models\AMPDSCode;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AMPDSCodeResource extends Resource
{
    protected static ?string $model = AMPDSCode::class;
    protected static ?string $label = 'AMPDS Code';
    protected static ?string $pluralLabel = 'AMPDS Codes';
    protected static ?string $navigationLabel = 'AMPDS Codes';
    protected static ?string $slug = 'ampds-codes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code'),
                TextInput::make('description'),
                Toggle::make('arrest_code'),
                Toggle::make('far_code'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                IconColumn::make('arrest_code')
                    ->boolean(),
                IconColumn::make('far_code')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('arrest_code')
                    ->label('Arrest Code?')
                    ->placeholder('All'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAMPDSCodes::route('/'),
        ];
    }
}
