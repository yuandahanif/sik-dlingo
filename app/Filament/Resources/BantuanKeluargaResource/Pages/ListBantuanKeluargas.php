<?php

namespace App\Filament\Resources\BantuanKeluargaResource\Pages;

use App\Filament\Resources\BantuanKeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBantuanKeluargas extends ListRecords
{
    protected static string $resource = BantuanKeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
