<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalloutResource\Pages\CreateCallout;
use App\Filament\Resources\CalloutResource\Pages\EditCallout;
use App\Filament\Resources\CalloutResource\Pages\ListCallouts;
use App\Filament\Resources\CalloutResource\Pages\ViewCallout;
use App\Models\Callout;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CalloutResource extends Resource
{
    protected static ?string $model = Callout::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-up-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('incident_number')
                    ->label('CAD Number')
                    ->required(),
                DateTimePicker::make('incident_date')
                    ->label('Incident Date')
                    ->seconds(false)
                    ->default(now())
                    ->required(),
                TextInput::make('ampds_code')
                    ->label('AMPDS Code')
                    ->required(),
                TextInput::make('age')
                    ->required(),
                Select::make('gender')
                    ->options(['Unknown' => 'Unknown', 'Male' => 'Male', 'Female' => 'Female'])
                    ->required(),
                Select::make('mobilised')
                    ->label('Mobilised?')
                    ->options(['No' => 'No', 'Yes' => 'Yes'])
                    ->live()
                    ->required(),
                Select::make('medical_facility')
                    ->label('Medical Facility?')
                    ->options(['No' => 'No', 'Yes' => 'Yes'])
                    ->live()
                    ->visible(fn(Get $get): bool => match ($get('mobilised')) {
                        'No' => true,
                        default => false,
                    }),
                Select::make('attended')
                    ->label('Attended?')
                    ->options(['No' => 'No', 'Yes' => 'Yes'])
                    ->live()
                    ->visible(fn(Get $get): bool => match ($get('mobilised')) {
                        'Yes' => true,
                        default => false,
                    }),
                Select::make('ohca_at_scene')
                    ->label('OHCA At Scene?')
                    ->options(['Yes' => 'Yes', 'No' => 'No'])
                    ->visible(fn(Get $get): bool => match ($get('attended')) {
                        'Yes' => true,
                        default => false,
                    })
                    ->live(),
                Select::make('bystander_cpr')
                    ->label('Bystander CPR?')
                    ->options(['Yes' => 'Yes', 'No' => 'No'])
                    ->visible(fn(Get $get): bool => match ($get('ohca_at_scene')) {
                        'Yes' => true,
                        default => false,
                    }),
                Select::make('source_of_aed')
                    ->label('Source Of AED')
                    ->options(['CFR', 'PAD', 'NAS', 'Fire', 'Garda', 'Other'])
                    ->visible(fn(Get $get): bool => match ($get('ohca_at_scene')) {
                        'Yes' => true,
                        default => false,
                    }),
                TextInput::make('number_of_shocks_given')
                    ->label('Number Of Shocks Given')
                    ->numeric()
                    ->visible(fn(Get $get): bool => match ($get('ohca_at_scene')) {
                        'Yes' => true,
                        default => false,
                    }),
                Select::make('rosc_achieved')
                    ->label('ROSC Achieved?')
                    ->options(['Yes' => 'Yes', 'No' => 'No'])
                    ->visible(fn(Get $get): bool => match ($get('ohca_at_scene')) {
                        'Yes' => true,
                        default => false,
                    })
                    ->live(),
                Select::make('patient_transported')
                    ->label('Patient Transported?')
                    ->options(['Yes' => 'Yes', 'No' => 'No'])
                    ->visible(function (Get $get) {
                        if ($get('attended') === 'Yes') {
                            if ($get('ohca_at_scene') === 'No' || $get('rosc_achieved') === 'Yes') {
                                return true;
                            }
                        }

                        return false;
                    }),
                Textarea::make('notes'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('incident_number')
                    ->label('CAD Number')
                    ->searchable(),
                TextColumn::make('incident_date')
                    ->label('Date/Time')
                    ->date('d/m/Y H:i')
                    ->searchable(),
                TextColumn::make('ampds_code')
                    ->label('AMPDS Code')
                    ->searchable(),
                IconColumn::make('attended')->boolean(fn(string $state) => $state === 'Yes'),
            ])
            ->filters([
                TernaryFilter::make('attended')
                    ->placeholder('Attended and Not Attended')
                    ->trueLabel('Attended')
                    ->falseLabel('Not Attended')
                    ->queries(
                        true: fn(Builder $query) => $query->where('attended', '=', 'Yes'),
                        false: fn(Builder $query) => $query->where('attended', '=', 'No'),
                    ),
                TernaryFilter::make('ohca_at_scene')
                    ->label('OHCA At Scene?')
                    ->placeholder('All')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->queries(
                        true: fn(Builder $query) => $query->where('ohca_at_scene', '=', 'Yes'),
                        false: fn(Builder $query) => $query->where('ohca_at_scene', '=', 'No'),
                    )
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCallouts::route('/'),
            'create' => CreateCallout::route('/create'),
            'view' => ViewCallout::route('/{record}'),
            'edit' => EditCallout::route('/{record}/edit'),
        ];
    }
}
