<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKeluargaResource\Pages;
use App\Filament\Resources\KartuKeluargaResource\RelationManagers;
use App\Filament\Resources\PendudukResource\Pages\ViewPenduduk;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

use App\Models\KartuKeluarga;
use App\Models\KartuKeluargaPenduduk;
use App\Models\Penduduk;
use Filament\Tables\Filters\SelectFilter;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class KartuKeluargaResource extends Resource
{
    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $navigationIcon = 'ri-file-paper-line';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'kartu-keluarga';

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('no_kk')
                ->label('Nomer KK')
                ->length(16)
                ->autocomplete('off')
                ->required(),
            Select::make('status_dtks')
                ->label('Status DTKS')
                ->native(false)
                ->options(KartuKeluarga::$status_dtks)
                ->required(),
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ...static::getCreateForm(),
                Repeater::make('anggota_keluarga')
                    ->relationship()
                    ->schema([
                        Select::make('penduduk_id')
                            ->label("Nama")
                            ->native(false)
                            ->searchable(['nama', 'nik'])
                            ->options(function () {
                                $kv = [];
                                $penduduk = Penduduk::get(['nik', 'nama', 'id']);
                                foreach ($penduduk as $key => $value) {
                                    $kv[$value->id] = $value->nik . ' - ' . $value->nama;
                                }

                                return $kv;
                            }),
                        Select::make('status_dalam_keluarga')
                            ->label('Status Dalam Keluarga')
                            ->required()
                            ->options(KartuKeluargaPenduduk::$status_dalam_keluarga)
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('no_kk')->searchable()->label('Nomer KK'),
                TextColumn::make('anggota_keluarga_count')->counts('anggota_keluarga')->label('Anggota KK')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('tanah_keluarga_count')->counts('tanah_keluarga')->label('Jumlah Tanah')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status_dtks')->sortable()->label('Status DTKS')
                    ->alignCenter(),
                TextColumn::make('bantuan_count')->counts('bantuan')->label('Jumlah Bantuan')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_ekonomi')->options(KartuKeluarga::$status_dtks)->label('Status DTKS')->native(false)->columnSpan(1),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Fieldset::make('Data Kartu Keluarga')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_kk')
                            ->label('Nomer KK'),
                        Infolists\Components\TextEntry::make('status_dtks')
                            ->label('Status DTKS'),
                        Infolists\Components\RepeatableEntry::make('anggota_keluarga_belong')
                            ->label('Anggota Keluarga')
                            ->columnSpanFull()
                            ->columns(5)
                            ->schema([
                                Infolists\Components\TextEntry::make('nama')
                                    ->label("Nama")->columnSpan(2)
                                    ->url(fn(Penduduk $record): string => route(ViewPenduduk::getRouteName(), ['record' => $record->id])),
                                Infolists\Components\TextEntry::make('kartu_keluarga.status_dalam_keluarga')
                                    ->label('Status Dalam Keluarga'),
                                Infolists\Components\RepeatableEntry::make('ayah')
                                    ->label('Ayah Kandung')
                                    ->contained(false)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('nama')
                                            ->listWithLineBreaks()
                                            ->label('')
                                            ->url(fn(Penduduk $record): string => route(ViewPenduduk::getRouteName(), ['record' => $record->id])),

                                    ]),
                                Infolists\Components\RepeatableEntry::make('ibu')
                                    ->label('Ibu Kandung')
                                    ->contained(false)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('nama')
                                            ->listWithLineBreaks()
                                            ->label('')
                                            ->url(fn(Penduduk $record): string => route(ViewPenduduk::getRouteName(), ['record' => $record->id])),

                                    ]),
                            ]),
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
            'view' => Pages\ViewKartuKeluarga::route('/{record}'),
            'edit' => Pages\EditKartuKeluarga::route('/{record}/edit'),
        ];
    }
}
