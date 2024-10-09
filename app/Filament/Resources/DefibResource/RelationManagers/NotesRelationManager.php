<?php

declare(strict_types=1);

namespace App\Filament\Resources\DefibResource\RelationManagers;

use Filament\Actions\StaticAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('note')
                    ->required()
                    ->rows(5),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note')
            ->columns([
                TextColumn::make('note')->limit(50),
                TextColumn::make('created_at')->date('d/m/Y H:i:s')->sortable(),
                TextColumn::make('updated_at')->date('d/m/Y H:i:s')->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->label('Add Note'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('View Note')
                    ->modalContent(function ($record) {
                        return view('filament.modals.view-note', ['note' => new HtmlString(sprintf('<pre style="font-family: inherit">%s</pre>', $record->note))]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(function (StaticAction $action) {
                        $action->button()->label('Close')->shouldClose();
                    })
                    ->closeModalByClickingAway()
                    ->closeModalByEscaping(),
                EditAction::make()->modalHeading('Edit Note'),
                DeleteAction::make()->modalHeading('Are you sure?'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordTitle('Note');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
