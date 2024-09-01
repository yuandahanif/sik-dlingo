<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class ChartFilter extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.widgets.chart-filter';

    protected static ?string $heading = 'Filter';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 1;
    public ?array $data = [];
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()->schema([
                    DatePicker::make('from')
                        ->label('Waktu Mulai')
                        ->live()
                        ->native(false)
                        ->default(now()->subYears(10)->format('Y-m-d'))
                        ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateFromDate', from: $state)),
                    DatePicker::make('to')
                        ->live()
                        ->default(now()->format('Y-m-d'))
                        ->label('Waktu Selesai')
                        ->native(false)
                        ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateToDate', to: $state)),
                ]),
            ]);
    }
}
