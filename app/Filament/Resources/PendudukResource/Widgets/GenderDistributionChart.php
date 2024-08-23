<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Penduduk Berdasarkan Jenis Kelamin';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $maleCount = Penduduk::where('jenis_kelamin', 'Laki-laki')->count();
        $femaleCount = Penduduk::where('jenis_kelamin', 'Perempuan')->count();

        return [
            'datasets' => [
                [
                    'data' => [$maleCount, $femaleCount], 
                    'backgroundColor' => ['blue', 'pink'], 
                    'label' => 'Jumlah Penduduk',
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'], 
        ];
    }

    protected function getType(): string
    {
        return 'pie'; 
    }
}
