<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKeluargaResource\Pages;
use App\Filament\Resources\KartuKeluargaResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

use App\Models\KartuKeluarga;
use App\Models\KartuKeluargaPenduduk;
use App\Models\Penduduk;

class KartuKeluargaResource extends Resource
{
    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $navigationIcon = 'ri-file-paper-line';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'kartu-keluarga';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('no_kk')
                    ->label('Nomer KK')
                    ->length(16)
                    ->autocomplete('off')
                    ->required(),
                Select::make('status_ekonomi')
                    ->label('Status Ekonomi')
                    ->options([
                        'mampu' => 'Mampu',
                        'tidak_mampu' => 'Tidak Mampu',
                    ])
                    ->required(),
                Repeater::make('anggota_keluarga')
                    ->relationship()
                    ->schema([
                        Select::make('penduduk_id')
                            ->label("Nama")
                            ->native(false)
                            ->searchable()
                            ->options(Penduduk::pluck('nama', 'id')),
                        Select::make('status_dalam_keluarga')
                            ->label('Status Dalam Keluarga')
                            ->required()
                            ->options(KartuKeluargaPenduduk::status_dalam_keluarga())
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_kk')->searchable()->label('Nomer KK'),
                TextColumn::make('anggota_keluarga_count')->counts('anggota_keluarga')->label('Anggota KK')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('tanah_keluarga_count')->counts('tanah_keluarga')->label('Jumlah Tanah')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status_ekonomi')->sortable()->label('Status Ekonomi')
                    ->alignCenter(),
                TextColumn::make('bantuan_count')->counts('bantuan')->label('Jumlah Bantuan')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartuKeluargas::route('/'),
            'create' => Pages\CreateKartuKeluarga::route('/create'),
            'edit' => Pages\EditKartuKeluarga::route('/{record}/edit'),
        ];
    }
}
