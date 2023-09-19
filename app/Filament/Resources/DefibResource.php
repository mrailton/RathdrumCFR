<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DefibResource\Pages\CreateDefib;
use App\Filament\Resources\DefibResource\Pages\EditDefib;
use App\Filament\Resources\DefibResource\Pages\ListDefibs;
use App\Filament\Resources\DefibResource\Pages\ViewDefib;
use App\Filament\Resources\DefibResource\RelationManagers\InspectionsRelationManager;
use App\Filament\Resources\DefibResource\RelationManagers\NotesRelationManager;
use App\Models\Defib;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DefibResource extends Resource
{
    protected static ?string $model = Defib::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                TextInput::make('coordinates')
                    ->maxLength(255),
                Toggle::make('display_on_map')
                    ->required(),
                TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                TextInput::make('serial')
                    ->maxLength(255),
                TextInput::make('owner')
                    ->required()
                    ->maxLength(255)
                    ->default('RathdrumCFR'),
                DatePicker::make('last_serviced_at')
                    ->label('Last Serviced'),
                DatePicker::make('last_inspected_at')
                    ->label('Last Inspected On')
                    ->readOnly(),
                TextInput::make('last_inspected_by')
                    ->label('Last Inspected By')
                    ->readOnly(),
                DatePicker::make('pads_expire_at')
                    ->label('Pads Expiry')
                    ->readOnly(),
                DatePicker::make('battery_expires_at')
                    ->label('Battery Expiry')
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('display_on_map')
                    ->label('Display On Map')
                    ->boolean(),
                TextColumn::make('last_inspected_by')
                    ->label('Last Inspected By')
                    ->searchable(),
                TextColumn::make('last_inspected_at')
                    ->label('Last Inspcted On')
                    ->date()
                    ->sortable(),
                TextColumn::make('pads_expire_at')
                    ->label('Pads Expire On')
                    ->date()
                    ->sortable(),
                TextColumn::make('battery_expires_at')
                    ->label('Battery Expires On')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InspectionsRelationManager::make(),
            NotesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDefibs::route('/'),
            'create' => CreateDefib::route('/create'),
            'view' => ViewDefib::route('/{record}'),
            'edit' => EditDefib::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
