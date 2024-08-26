<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Widgets\ChartWidget;

use App\Models\Dusun;

class PersebaranPenduduk extends ChartWidget
{
    protected static ?string $heading = 'jumlah Penduduk per Dusun';

    protected int | string | array $columnSpan = 1;

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $dusun_label = Dusun::query()
            ->select('nama')
            ->get()
            ->pluck('nama')
            ->toArray();

        $count_penduduk_per_dusun = Dusun::query()
            ->selectRaw('COUNT(*) as count')
            ->withCount('penduduk')
            ->groupBy('id')
            ->get()
            ->pluck('penduduk_count')
            ->toArray();

        return [
            'datasets' => [
                [
                    'backgroundColor' => ['lime', 'salmon', 'skyblue', 'yellow', 'purple', 'orange', 'brown', 'black'],
                    'data' => $count_penduduk_per_dusun,
                ],
            ],
            'labels' => $dusun_label,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
