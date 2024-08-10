<?php

namespace App\Filament\Resources\RtResource\Pages;

use App\Filament\Resources\RtResource;
use App\Models\Rt;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditRt extends EditRecord
{
    protected static string $resource = RtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        Log::info($data);
        return $data;
    }
}
