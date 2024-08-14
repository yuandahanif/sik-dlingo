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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

use App\Models\KartuKeluarga;
use App\Models\KartuKeluargaPenduduk;
use App\Models\Penduduk;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;

use Filament\Infolists;
use Filament\Infolists\Infolist;

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
                    ->native(false)
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
                SelectFilter::make('status_ekonomi')->options(KartuKeluarga::$status_ekonomi)->label('Status Ekonomi')->native(false)->columnSpan(1),
            ])
            ->actions([
                ViewAction::make('detail')
                    ->fillForm(fn(KartuKeluarga $record): array => [
                        'no_kk' => $record->no_kk,
                        'status_ekonomi' => $record->status_ekonomi,
                        'anggota_keluarga' => $record->anggota_keluarga(),
                    ])
                    ->form([
                        TextInput::make('no_kk')
                            ->label('Nomer KK'),
                        Select::make('status_ekonomi')
                            ->label('Status Ekonomi')
                            ->native(false)
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
                            ->extraItemActions([
                                Action::make('LihatDetailPenduduk')->label('Lihat Penduduk')
                                    ->icon('fas-user')
                                    ->url(function (array $arguments, Repeater $component): string {
                                        $data = $component->getItemState($arguments['item']);
                                        return route(ViewPenduduk::getRouteName(), ['record' => $data['penduduk_id']]);
                                    }),
                            ])
                            ->columns(2)
                            ->columnSpan(2)
                    ]),
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
                        Infolists\Components\TextEntry::make('status_ekonomi')
                            ->label('Status Ekonomi'),
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
                                Infolists\Components\TextEntry::make('orang_tua_kandung.parent')
                                    ->label('Orang Tua'),

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
