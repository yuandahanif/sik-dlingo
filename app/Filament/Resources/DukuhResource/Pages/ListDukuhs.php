<?php

namespace App\Filament\Resources\DukuhResource\Pages;

use App\Filament\Resources\DukuhResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDukuhs extends ListRecords
{
    protected static string $resource = DukuhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
