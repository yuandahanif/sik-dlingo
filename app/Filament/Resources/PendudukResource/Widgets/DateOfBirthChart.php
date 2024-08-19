<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Actions\Action;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class DateOfBirthChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Lahir dan Meninggal';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';


    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateAuthor')
                ->label('Update Author')
                ->form([
                    Select::make('authorId')
                        ->label('Author')
                        // ->options(User::query()->pluck('name', 'id'))
                        ->required(),
                ])
                // ->action(function (array $data, Post $record): void {
                //     $record->author()->associate($data['authorId']);
                //     $record->save();
                // })
        ];
    }



    protected function getData(): array
    {
        $years_of_birth = Penduduk::query()
            ->selectRaw('YEAR(tanggal_lahir) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $years_of_birth_count = Penduduk::query()
            ->selectRaw('YEAR(tanggal_lahir) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('count')
            ->toArray();

        $years_of_death = Penduduk::query()
            ->selectRaw('YEAR(tanggal_meninggal) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        return [
            'datasets' => [
                [

                    'borderColor' => 'lime',
                    'color' => 'lime',
                    'label' => 'Jumlah Kelahiran',
                    'data' => $years_of_birth_count,
                ],
                [
                    'borderColor' => 'red',
                    'color' => 'red',
                    'label' => 'Jumlah Kematian',
                    'data' => $years_of_death,
                ],
            ],
            'labels' => $years_of_birth,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
