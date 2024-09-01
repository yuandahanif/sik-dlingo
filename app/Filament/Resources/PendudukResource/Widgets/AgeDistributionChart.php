<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class AgeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Penduduk Berdasarkan Usia';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $currentDate = Carbon::now();
        $ageDistribution = [
            'Bayi dan Balita (0-5)' => 0,
            'Anak-anak (6-9)' => 0,
            'Remaja (10-18)' => 0,
            'Dewasa (19-59)' => 0,
            'Lansia (60++)' => 0,
        ];

        foreach (Penduduk::all(['tanggal_lahir']) as $penduduk) {
            $age = Carbon::parse($penduduk->tanggal_lahir)->diffInYears($currentDate);

            if ($age < 5) {
                $ageDistribution['Bayi dan Balita (0-5)']++;
            } elseif ($age <= 9) {
                $ageDistribution['Anak-anak (6-9)']++;
            } elseif ($age <= 18) {
                $ageDistribution['Remaja (10-18)']++;
            } elseif ($age <= 59) {
                $ageDistribution['Dewasa (19-59)']++;
            } else {
                $ageDistribution['Lansia (60++)']++;
            }
        }

        return [
            'datasets' => [
                [
                    'data' => array_values($ageDistribution),  
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], 
                    'label' => 'Jumlah Penduduk',
                ],
            ],
            'labels' => array_keys($ageDistribution), 
        ];
    }

    protected function getType(): string
    {
        return 'bar'; 
    }
}
