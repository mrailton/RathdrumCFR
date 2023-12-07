<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CalloutResource\Pages\CreateCallout;
use App\Filament\Resources\CalloutResource\Pages\EditCallout;
use App\Filament\Resources\CalloutResource\Pages\ListCallouts;
use App\Filament\Resources\CalloutResource\Pages\ViewCallout;
use App\Models\AMPDSCode;
use App\Models\Callout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CalloutResource extends Resource
{
    protected static ?string $model = Callout::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-up-right';

    public static function form(Form $form): Form
    {
        $ampds = [];

        foreach (AMPDSCode::all() as $code) {
            $ampds[$code->code] = "{$code->code} - {$code->description}";
        }


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
                Select::make('ampds_code')
                    ->label('AMPDS Code')
                    ->options($ampds)
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('age')
                    ->required(),
                Select::make('gender')
                    ->options(['Unknown' => 'Unknown', 'Male' => 'Male', 'Female' => 'Female'])
                    ->required(),
                Select::make('mobilised')
                    ->label('Mobilised?')
                    ->options([false => 'No', true => 'Yes'])
                    ->live()
                    ->required(),
                Select::make('medical_facility')
                    ->label('Medical Facility?')
                    ->options([false => 'No', true => 'Yes'])
                    ->live()
                    ->visible(fn (Get $get): bool => null !== $get('mobilised') && ! $get('mobilised')),
                Select::make('members')
                    ->relationship('members', 'name')
                    ->preload()
                    ->multiple()
                    ->visible(fn (Get $get): bool => null !== $get('mobilised') && $get('mobilised')),
                Select::make('attended')
                    ->label('Attended?')
                    ->options([false => 'No', true => 'Yes'])
                    ->live()
                    ->visible(fn (Get $get): bool => null !== $get('mobilised') && $get('mobilised')),
                Select::make('ohca_at_scene')
                    ->label('OHCA At Scene?')
                    ->options([false => 'No', true => 'Yes'])
                    ->live()
                    ->visible(fn (Get $get): bool => null !== $get('attended') && $get('attended')),
                Select::make('bystander_cpr')
                    ->label('Bystander CPR?')
                    ->options([false => 'No', true => 'Yes'])
                    ->visible(fn (Get $get): bool => null !== $get('ohca_at_scene') && $get('ohca_at_scene')),
                Select::make('source_of_aed')
                    ->label('Source Of AED')
                    ->options(['CFR', 'PAD', 'NAS', 'Fire', 'Garda', 'Other'])
                    ->visible(fn (Get $get): bool => null !== $get('ohca_at_scene') && $get('ohca_at_scene')),
                TextInput::make('number_of_shocks_given')
                    ->label('Number Of Shocks Given')
                    ->numeric()
                    ->visible(fn (Get $get): bool => null !== $get('ohca_at_scene') && $get('ohca_at_scene')),
                Select::make('rosc_achieved')
                    ->label('ROSC Achieved?')
                    ->options([false => 'No', true => 'Yes'])
                    ->live()
                    ->visible(fn (Get $get): bool => null !== $get('ohca_at_scene') && $get('ohca_at_scene')),
                Select::make('patient_transported')
                    ->label('Patient Transported?')
                    ->options([false => 'No', true => 'Yes'])
                    ->visible(function (Get $get) {
                        if (null !== $get('attended') && $get('attended')) {
                            if (null !== $get('ohca_at_scene') && ! $get('ohca_at_scene') || null !== $get('rosc_achieved') && $get('rosc_achieved')) {
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
                    ->date('M j, Y H:i')
                    ->searchable(),
                TextColumn::make('ampds_code')
                    ->label('AMPDS Code')
                    ->searchable(),
                IconColumn::make('mobilised')
                    ->boolean()
                    ->label('Mobilised?'),
                IconColumn::make('attended')
                    ->boolean()
                    ->label('Attended?'),
                IconColumn::make('medical_facility')
                    ->boolean()
                    ->label('Medical Facility?'),
            ])
            ->filters([
                TernaryFilter::make('attended')
                    ->label('Attended?')
                    ->placeholder('All'),
                TernaryFilter::make('ohca_at_scene')
                    ->label('OHCA At Scene?')
                    ->placeholder('All'),
                Filter::make('incident_date')
                    ->form([
                        Fieldset::make('Callout Date')
                            ->schema([
                                DatePicker::make('from')->default(now()->startOfYear()),
                                DatePicker::make('to')->default(now()->endOfYear()),
                            ])
                            ->columns(1)
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query) => $query->whereDate('incident_date', '>=', $data['from'])
                            )
                            ->when(
                                $data['to'] ?? null,
                                fn(Builder $query) => $query->whereDate('incident_date', '<=', $data['to'])
                            );
                    })
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('incident_date');
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
