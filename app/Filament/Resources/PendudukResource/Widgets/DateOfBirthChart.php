<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Actions\Action;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class DateOfBirthChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Lahir dan Meninggal';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    public Carbon $fromDate;
    public Carbon $toDate;

    #[On('updateFromDate')]
    public function updateFromDate(string $from): void
    {
        $this->fromDate = Carbon::parse($from);
        $this->updateChartData();
    }
    #[On('updateToDate')]
    public function updateToDate(string $to): void
    {
        $this->toDate = Carbon::parse($to);
        $this->updateChartData();
    }

    protected function getData(): array
    {
        $fromDate = $this->fromDate ?? now()->subYears(10);
        $toDate = $this->toDate ?? now();

        $years_of_birth = Penduduk::query()
            ->selectRaw('YEAR(tanggal_lahir) as year')
            ->whereBetween('tanggal_lahir', [$fromDate, $toDate])
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $years_of_birth_count = Penduduk::query()
            ->selectRaw('YEAR(tanggal_lahir) as year, COUNT(*) as count')
            ->whereBetween('tanggal_lahir', [$fromDate, $toDate])
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('count')
            ->toArray();

        $years_of_death = Penduduk::query()
            ->selectRaw('YEAR(tanggal_meninggal) as year')
            ->whereBetween('tanggal_lahir', [$fromDate, $toDate])
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
                    'label' => 'Jumlah Meninggal',
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
