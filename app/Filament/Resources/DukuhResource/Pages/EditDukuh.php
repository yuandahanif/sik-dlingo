<?php

namespace App\Filament\Resources\DukuhResource\Pages;

use App\Filament\Resources\DukuhResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDukuh extends EditRecord
{
    protected static string $resource = DukuhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
