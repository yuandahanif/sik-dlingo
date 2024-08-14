<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

class ViewPenduduk extends ViewRecord
{
    protected static string $resource = PendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->record($this->record),

            Action::make('editRelasiKeluarga')
                ->url(fn(): string => route(EditPendudukRelasiKeluarga::getRouteName(), ['record' => $this->record->id])),
            DeleteAction::make()
                ->record($this->record),
        ];
    }
}
