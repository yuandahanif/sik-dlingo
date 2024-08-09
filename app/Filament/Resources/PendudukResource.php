<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages;
use App\Filament\Resources\PendudukResource\RelationManagers;
use App\Models\Penduduk;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $navigationIcon = 'fas-users-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama')->required()
                    ->maxLength(255),
                TextInput::make('nik'),
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
            ])
            ->filters([
                $ageFilter,
            ], layout: FiltersLayout::Modal)
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->color('primary')
                    ->fillForm(fn(Penduduk $record): array => [
                        'nama' => $record->nama,
                        'alamat' => "Rt " . $record->rt->nama . ", Dusun " . $record->rt->dusun->nama . ", " . $record->alamat,
                        'nik' => $record->nik,
                        'jenis_kelamin' => $record->jenis_kelamin,
                        'tempat_tanggal_lahir' => [$record->tempat_lahir, " " . Carbon::parse($record->tanggal_lahir)->locale('id')->format('d F Y')],
                        'agama' => $record->agama,
                        'pekerjaan' => $record->pekerjaan,
                        'status_pernikahan' => $record->status_pernikahan,
                        // 'status_kependudukan' => $record->status_kependudukan,
                        // 'status' => $record->status,
                    ])
                    ->form([
                        TextInput::make('nik')
                            ->label('NIK')->required(),
                        TextInput::make('nama')
                            ->label('Nama')->required(),
                        TextInput::make('alamat')
                            ->label('Alamat')->required(),
                        TextInput::make('tempat_tanggal_lahir')
                            ->label('Tempat/tanggal lahir')->required(),
                        TextInput::make('jenis_kelamin')
                            ->label('Jenis Kelamin')->required(),
                        TextInput::make('agama')
                            ->label('Agama')->required(),
                        TextInput::make('pekerjaan')
                            ->label('Pekerjaan')->required(),
                    ])
                    ->action(function (array $data, Penduduk $record): void {})->disabledForm()->modalSubmitAction(false),
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
