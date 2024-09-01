<?php

namespace App\Filament\Resources\KategoriBantuanResource\Pages;

use App\Filament\Resources\KategoriBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriBantuans extends ManageRecords
{
    protected static string $resource = KategoriBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
