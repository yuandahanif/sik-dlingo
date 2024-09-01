<?php

namespace App\Filament\Resources\BantuanPendudukResource\Pages;

use App\Filament\Resources\BantuanPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBantuanPenduduks extends ListRecords
{
    protected static string $resource = BantuanPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
