<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Label')
                    ->tabs([
                        Tab::make('Contact')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('status')
                                    ->required()
                                    ->options(['inactive' => 'Inactive', 'active' => 'Active'])
                                    ->default('Inactive'),
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('Responder'),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                        Tab::make('Address')
                            ->schema([
                                TextInput::make('address_1')
                                    ->maxLength(255),
                                TextInput::make('address_2')
                                    ->maxLength(255),
                                TextInput::make('eircode')
                                    ->maxLength(255),
                            ]),
                        Tab::make('Certs')
                            ->schema([
                                TextInput::make('cfr_certificate_number')
                                    ->maxLength(255),
                                DatePicker::make('cfr_certificate_expiry'),
                                DatePicker::make('volunteer_declaration'),
                                TextInput::make('garda_vetting_id')
                                    ->maxLength(255),
                                DatePicker::make('garda_vetting_date'),
                                DatePicker::make('cism_completed'),
                                DatePicker::make('child_first_completed'),
                                DatePicker::make('ppe_community_completed'),
                                DatePicker::make('ppe_acute_completed'),
                                DatePicker::make('hand_hygiene_completed'),
                                DatePicker::make('hiqa_completed'),
                                DatePicker::make('covid_return_completed'),
                                DatePicker::make('ppe_assessment_completed'),
                            ])
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('cfr_certificate_expiry')
                    ->date()
                    ->sortable(),
                TextColumn::make('garda_vetting_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\NotesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
