<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPenduduks extends ListRecords
{
    protected static string $resource = PendudukResource::class;

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Hidup' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'hidup')),
            'Meninggal' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'meninggal')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
