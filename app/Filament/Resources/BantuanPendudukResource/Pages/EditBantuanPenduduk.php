<?php

namespace App\Filament\Resources\BantuanPendudukResource\Pages;

use App\Filament\Resources\BantuanPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBantuanPenduduk extends EditRecord
{
    protected static string $resource = BantuanPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
