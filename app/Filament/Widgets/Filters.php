<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;

class Filters extends Widget implements HasForms
{
    use HasWidgetShield;
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.filters';

    protected string|int|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static array $public = [];
    public array $dates;

    public function mount(): void
    {
        self::$public['from'] = now()->startOfYear();
        self::$public['to'] = now()->endOfYear();

        $this->dates['from'] = self::$public['from']->format('Y-m-d');
        $this->dates['to'] = self::$public['to']->format('Y-m-d');
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('dates')
            ->schema([
                Grid::make()
                    ->schema([
                        DatePicker::make('from')
                            ->live()
                            ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateFromDate', from: $state)),
                        DatePicker::make('to')
                            ->live()
                            ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateToDate', to: $state)),
                    ])
            ]);
    }
}
