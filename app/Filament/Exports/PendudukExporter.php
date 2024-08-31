<?php

namespace App\Filament\Exports;

use App\Models\Penduduk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PendudukExporter extends Exporter
{
    protected static ?string $model = Penduduk::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nama'),
            ExportColumn::make('nik'),
            ExportColumn::make('rt.nama')->label('RT'),
            ExportColumn::make('jenis_kelamin'),
            ExportColumn::make('tempat_lahir'),
            ExportColumn::make('tanggal_lahir'),
            ExportColumn::make('alamat')->state(function (Penduduk $record): string {
                return 'RT ' . $record->rt->rt . ', ' . $record->alamat;
            }),
            ExportColumn::make('pekerjaan'),
            ExportColumn::make('status_kependudukan'),
            ExportColumn::make('status'),
            ExportColumn::make('agama'),
            ExportColumn::make('status_pernikahan'),
            ExportColumn::make('tanggal_meninggal'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('kartu_keluarga.kartu_keluarga.no_kk')->label('No. KK'),
            ExportColumn::make('kartu_keluarga.kartu_keluarga.status_dtks')->label('Status DTKS'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your penduduk export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
