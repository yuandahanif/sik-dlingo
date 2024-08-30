<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PendudukExporter;
use App\Filament\Resources\KartuKeluargaResource\Pages\ViewKartuKeluarga;
use App\Filament\Resources\PendudukResource\Pages;
use App\Filament\Resources\PendudukResource\Pages\ViewPenduduk;
use App\Filament\Resources\PendudukResource\RelationManagers;
use App\Models\Dusun;
use App\Models\KartuKeluargaPenduduk;
use App\Models\Penduduk;
use App\Models\Rt;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Get;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;

use Filament\Infolists;
use Filament\Infolists\Infolist;

use Illuminate\Support\Arr;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $navigationIcon = 'fas-users-line';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'penduduk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nik')
                    ->disabled(fn(string $operation) => $operation == 'edit')
                    ->unique(table: Penduduk::class, column: 'nik', ignoreRecord: true)
                    ->label('NIK')
                    ->numeric()
                    ->length(16)
                    ->autocomplete('off')
                    ->required()
                    ->live()
                    ->validationMessages([
                        'unique' => 'NIK sudah ada.',
                    ]),
                TextInput::make('nama')->label('Nama')->autocomplete('off')->required(),
                Select::make('rt_id')->relationship('rt', 'nama')->options(function () {
                    $kv = [];
                    $rts = Rt::with(['dusun'])->get();
                    foreach ($rts as $key => $value) {
                        $kv[$value->id] = $value->dusun->nama . ' - ' . $value->nama;
                    }
                    return $kv;
                })->native(false)->preload()->searchable()->required(),
                Select::make('jenis_kelamin')->options(Penduduk::$jenis_kelamin)->native(false)->required(),
                TextInput::make('tempat_lahir')->label('Tempat Lahir')->required(),
                DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->native(false)->locale('id')->maxDate(now())->required(),
                Select::make('agama')->options(Penduduk::$agama)->required()->native(false),
                Textarea::make('alamat')->required(),
                Select::make('status_pernikahan')->native(false)->options(Penduduk::$status_pernikahan)->required(),
                TextInput::make('pekerjaan')->label('Pekerjaan')->required(),
                Select::make('status_kependudukan')->options(Penduduk::$status_kependudukan)->native(false)->live()->default(null),
                Select::make('status')->options(Penduduk::$status)->native(false)->default('hidup')->required()->live(),
                DatePicker::make('tanggal_meninggal')->label('Tanggal meninggal')->native(false)->locale('id')->maxDate(now())->visible(fn(Get $get) => $get('status') == 'meninggal')->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $ageFilter =
            Filter::make('age')
            ->form([
                Fieldset::make('Usia')
                    ->schema([
                        TextInput::make('lowest_age')->numeric()->label('Batas Bawah')->prefix('Batas Bawah')->minValue(0)
                            ->maxValue(
                                function (Get $get): Int {
                                    $highest_age = (int) $get('highest_age') ?? 150;
                                    return $highest_age;
                                }
                            )
                            ->debounce(1000)
                            ->live(),
                        TextInput::make('highest_age')->numeric()->label('Batas Atas')->suffix('Batas Atas')->maxValue(150)
                            ->minValue(
                                function (Get $get): Int {
                                    $lowest_age =  (int) $get('lowest_age') ?? 0;
                                    return $lowest_age;
                                }
                            )
                            ->debounce(1000)
                            ->live(),

                    ])->columns(2),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        isset($data['lowest_age']),
                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '<=', Carbon::now()->subYears($data['lowest_age'])),
                    )
                    ->when(
                        isset($data['highest_age']),
                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '>=', Carbon::now()->subYears($data['highest_age'])),
                    );
            })->columnSpanFull();

        $dusunFilter =
            Filter::make('dusun')
            ->form([
                Fieldset::make('Alamat')
                    ->schema([
                        Select::make('dusun')->label('Dusun')
                            ->options(Dusun::pluck('nama', 'id'))
                            ->preload()
                            ->debounce(1000)
                            ->native(false)
                            ->multiple()
                            ->live(),
                        Select::make('rt_id')->label('RT')
                            ->native(false)
                            ->preload()
                            ->multiple()
                            ->disabled(fn(Get $get) => !$get('dusun'))
                            ->options(fn(Get $get) => Rt::whereIn('dusun_id', $get('dusun'))->pluck('nama', 'id'))
                            ->debounce(1000)
                            ->live(),

                    ])->columns(2),
            ])->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['rt_id'],
                        fn(Builder $query, $data): Builder => $query->whereIn('rt_id', $data),
                    );
            })->columnSpanFull();

        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(PendudukExporter::class)
            ])
            ->columns([
                TextColumn::make('nik')->searchable()->label('NIK'),
                TextColumn::make('nama')->searchable(),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin')->sortable(),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->sortable(),
                TextColumn::make('age')->label('Usia')->sortable(['tanggal_lahir']),
                TextColumn::make('agama')->label('Agama')->sortable(['agama']),
                TextColumn::make('bantuan_count')->counts('bantuan')->label('Jumlah Bantuan')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
            ])
            ->filters([
                $ageFilter,
                SelectFilter::make('jenis_kelamin')->options(Penduduk::$jenis_kelamin)->label('Jenis Kelamin')->native(false),
                SelectFilter::make('agama')->options(Penduduk::$agama)->label('Agama')->native(false)->columnSpan(1),
                SelectFilter::make('status')->options(Penduduk::$status)->label('Status')->native(false)->columnSpan(1),
                SelectFilter::make('status_kependudukan')->options(Penduduk::$status_kependudukan)->label('Status Kependudukan')->native(false)->columnSpan(1),
                $dusunFilter,
            ], layout: FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ])
            ->filtersFormWidth(MaxWidth::TwoExtraLarge)
            ->filtersFormColumns(2);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Fieldset::make('Data Penduduk')
                    ->schema([
                        Infolists\Components\TextEntry::make('nik')->label('NIK'),
                        Infolists\Components\TextEntry::make('kartu_keluarga.kartu_keluarga.no_kk')->label('No. KK')
                            ->url(fn(Penduduk $record): string => route(ViewKartuKeluarga::getRouteName(), ['record' => $record?->kartu_keluarga?->kartu_keluarga ?? '#'])),
                        Infolists\Components\TextEntry::make('nama'),
                        Infolists\Components\TextEntry::make('alamat')->state(function (Penduduk $record): string {
                            return 'RT ' . $record->rt->rt . ', ' . $record->alamat;
                        }),
                        Infolists\Components\TextEntry::make('tempat_tanggal_lahir')->label('Tempat/Tanggal lahir'),
                        Infolists\Components\TextEntry::make('agama')->label('Agama'),
                        Infolists\Components\TextEntry::make('pekerjaan')->label('Pekerjaan'),
                        Infolists\Components\TextEntry::make('status_kependudukan')->label('Status Kependudukan')
                            ->state(
                                fn(Penduduk $record) =>
                                is_null($record->status_kependudukan) ? Penduduk::$status_kependudukan['null'] : Penduduk::$status_kependudukan[$record->status_kependudukan]
                            ),
                        Infolists\Components\TextEntry::make('status')->label('Status'),
                        Infolists\Components\TextEntry::make('kartu_keluarga.kartu_keluarga')->label('Status Dalam keluarga')
                            ->state(function (Penduduk $record): string {
                                return KartuKeluargaPenduduk::$status_dalam_keluarga[$record->kartu_keluarga->status_dalam_keluarga];
                            }),
                    ]),
                Infolists\Components\Fieldset::make('Orang Tua')
                    ->schema([
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
                Infolists\Components\Fieldset::make('Anak')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('anak')
                            ->label('')
                            ->contained(false)
                            ->schema([
                                Infolists\Components\TextEntry::make('nama')
                                    ->listWithLineBreaks()
                                    ->label('')
                                    ->url(fn(Penduduk $record): string => route(ViewPenduduk::getRouteName(), ['record' => $record->id])),

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
            'index' => Pages\ListPenduduks::route('/'),
            'create' => Pages\CreatePenduduk::route('/create'),
            'view' => Pages\ViewPenduduk::route('/{record}'),
            'edit-contact' => Pages\EditPendudukRelasiKeluarga::route('/{record}/edit/relasi-keluarga'),
            'edit' => Pages\EditPenduduk::route('/{record}/edit'),
        ];
    }
}
