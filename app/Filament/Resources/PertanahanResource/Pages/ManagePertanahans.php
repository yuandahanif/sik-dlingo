<?php

namespace App\Filament\Resources\PertanahanResource\Pages;

use App\Filament\Resources\PertanahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePertanahans extends ManageRecords
{
    protected static string $resource = PertanahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
