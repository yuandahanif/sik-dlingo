<?php

namespace App\Filament\Resources\DukuhResource\Pages;

use App\Filament\Resources\DusunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDusun extends ListRecords
{
    protected static string $resource = DusunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
