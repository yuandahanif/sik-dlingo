<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages;
use App\Filament\Resources\PendudukResource\RelationManagers;
use App\Models\Penduduk;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Get;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
                TextInput::make('nik')->unique(fn(string $operation) => $operation == 'create')->label('NIK')->autocomplete('off')->required()->live()->disabled(fn(string $operation) => $operation == 'edit')->validationMessages([
                    'unique' => 'NIK sudah ada.',
                ]),
                TextInput::make('nama')->label('Nama')->autocomplete('off')->required(),
                Select::make('rt_id')->relationship('rt', 'nama')->native(false)->preload()->searchable()->required(),
                Select::make('jenis_kelamin')->options(Penduduk::$jenis_kelamin)->native(false)->required(),
                TextInput::make('tempat_lahir')->label('Tempat Lahir')->required(),
                DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->native(false)->locale('id')->maxDate(now())->required(),
                Select::make('agama')->options(Penduduk::$agama)->required(),
                Textarea::make('alamat')->required(),
                Select::make('status_pernikahan')->options(Penduduk::$status_pernikahan)->required(),
                TextInput::make('pekerjaan')->label('Pekerjaan')->required(),
                Select::make('status_kependudukan')->options(Penduduk::$status_kependudukan)->live()->default(null),
                Select::make('status')->options(Penduduk::$status)->default('hidup')->required()->live(),
                DatePicker::make('tanggal_meninggal')->label('Tanggal meninggal')->native(false)->locale('id')->maxDate(now())->visible(fn(Get $get) => $get('status') == 'meninggal')->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $ageFilter =
            Filter::make('created_at')
            ->form([
                Fieldset::make('Usia')
                    ->schema([
                        TextInput::make('lowest_age')->numeric()->label('Batas Bawah')->prefix('Batas Bawah')->minValue(0)
                            ->maxValue(
                                function (Get $get): Int {
                                    $highest_age = $get('highest_age') ?? 150;
                                    return $highest_age;
                                }
                            )
                            ->debounce(1000)
                            ->live(),
                        TextInput::make('highest_age')->numeric()->label('Batas Atas')->prefix('Batas Atas')->maxValue(150)
                            ->minValue(
                                function (Get $get): Int {
                                    $lowest_age = $get('lowest_age') ?? 0;
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
                        $data['lowest_age'],
                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '<=', Carbon::now()->subYears($date)),
                    )
                    ->when(
                        $data['highest_age'],
                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '>=', Carbon::now()->subYears($date)),
                    );
            });

        return $table
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
            ], layout: FiltersLayout::Modal)
            ->actions([
                ViewAction::make('detail')
                    ->fillForm(fn(Penduduk $record): array => [
                        'nama' => $record->nama,
                        'alamat' => "Rt " . $record->rt->nama . ", Dusun " . $record->rt->dusun->nama . ", " . $record->alamat,
                        'nik' => $record->nik,
                        'jenis_kelamin' => $record->jenis_kelamin,
                        'tempat_tanggal_lahir' => [$record->tempat_lahir, " " . Carbon::parse($record->tanggal_lahir)->locale('id')->format('d F Y')],
                        'agama' => Str::ucfirst($record->agama),
                        'pekerjaan' => $record->pekerjaan,
                        'status_kependudukan' => Str::ucfirst($record->status_kependudukan ?? "Belum diisi"),
                        'status' => Str::ucfirst($record->status ?? "Belum diisi"),
                    ])
                    ->form([
                        TextInput::make('nik')
                            ->label('NIK'),
                        TextInput::make('nama')
                            ->label('Nama'),
                        TextInput::make('alamat')
                            ->label('Alamat'),
                        TextInput::make('tempat_tanggal_lahir')
                            ->label('Tempat/tanggal lahir'),
                        TextInput::make('jenis_kelamin')
                            ->label('Jenis Kelamin'),
                        TextInput::make('agama')
                            ->label('Agama'),
                        TextInput::make('pekerjaan')
                            ->label('Pekerjaan'),
                        TextInput::make('status_kependudukan')
                            ->label('Status Kependudukan'),
                        TextInput::make('status')
                            ->label('Status'),
                    ]),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->filtersFormWidth(MaxWidth::TwoExtraLarge);
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
            'edit' => Pages\EditPenduduk::route('/{record}/edit'),
        ];
    }
}
