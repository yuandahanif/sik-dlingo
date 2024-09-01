<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $jumlah_penduduk = \App\Models\Penduduk::count();
        $jumlah_kartu_keluarga = \App\Models\KartuKeluarga::count();

        return [
            Stat::make('Jumlah Penduduk', Number::withLocale('id', fn() => Number::abbreviate($jumlah_penduduk, 1))),
            Stat::make('Jumlah Kartu Keluarga', Number::withLocale('id', fn() => Number::abbreviate($jumlah_kartu_keluarga, 1))),
        ];
    }
}
