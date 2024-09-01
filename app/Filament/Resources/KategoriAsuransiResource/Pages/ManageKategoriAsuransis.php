<?php

namespace App\Filament\Resources\KategoriAsuransiResource\Pages;

use App\Filament\Resources\KategoriAsuransiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriAsuransis extends ManageRecords
{
    protected static string $resource = KategoriAsuransiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
