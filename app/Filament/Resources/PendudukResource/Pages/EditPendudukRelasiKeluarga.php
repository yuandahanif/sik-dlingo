<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\PohonKeluarga;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;


use Filament\Infolists;

class EditPendudukRelasiKeluarga extends EditRecord
{
    protected static string $resource = PendudukResource::class;

    protected static ?string $title = 'Edit Relasi Keluarga';

    protected ?string $heading = 'Edit Relasi Keluarga';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->hidden(),
                TextInput::make('nik')->label('NIK')->disabled(),
                TextInput::make('nama')->disabled(),
                Repeater::make('pohon_keluarga')
                    ->addActionLabel('Tambah Relasi Keluarga')
                    ->columnSpanFull()
                    ->defaultItems(2)
                    ->relationship()
                    ->schema([
                        Select::make('parent_id')
                            ->searchable()
                            ->relationship('parent', 'nama')
                            ->native(false)
                            ->preload()
                            ->required(),
                        Select::make('hubungan')
                            ->options(PohonKeluarga::$hubungan)
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2)
            ]);
    }
}
