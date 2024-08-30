<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RtResource\Pages;
use App\Filament\Resources\RtResource\RelationManagers;
use App\Models\Penduduk;
use App\Models\Rt;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\Column;

class RtResource extends Resource
{
    protected static ?string $model = Rt::class;

    protected static ?string $navigationLabel = 'RT';

    protected static ?string $navigationIcon = 'healthicons-f-village';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'rt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama RT')
                    ->required(),
                TextInput::make('rt')
                    ->numeric()
                    ->label('Nomor RT')
                    ->required(),
                Select::make('dusun_id')
                    ->relationship(name: 'dusun', titleAttribute: 'nama')
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload(),
                Select::make('kepala_id')
                    ->relationship(name: 'kepala', titleAttribute: 'nama')
                    ->options(function () {
                        $kv = [];
                        $penduduk = Penduduk::get(['nik', 'nama', 'id']);
                        foreach ($penduduk as $key => $value) {
                            $kv[$value->id] = $value->nik . ' - ' . $value->nama;
                        }

                        return $kv;
                    })
                    ->label('Kepala RT')
                    ->native(false)
                    ->searchable()
                    ->preload()
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->label('Nama RT'),
                TextColumn::make('dusun.nama')
                    ->label('Dusun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kepala.nama')
                    ->searchable()
                    ->label('Kepala RT'),
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Database ID'),
                TextColumn::make('penduduk_count')->counts('penduduk')
                    ->label('Jumlah Penduduk')
                    ->sortable()
                    ->toggleable()
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
                SelectFilter::make('dusun')->relationship('dusun', 'nama')->multiple()->preload()->label('Dusun')->native(false)->columnSpan(1),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('detail')
                    ->fillForm(fn(Rt $record): array => [
                        'nama' => $record->nama,
                        'rt' => $record->rt,
                        'dusun' => $record->dusun->nama,
                        'kepala' => $record->kepala?->nama,
                    ])->form([
                        TextInput::make('nama')
                            ->label('Nama RT'),
                        TextInput::make('rt')
                            ->numeric()
                            ->label('Nomor RT'),
                        TextInput::make('dusun')
                            ->label('Nama Dusun'),
                        TextInput::make('kepala')
                            ->label('Kepala RT'),
                    ]),
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
            'index' => Pages\ListRts::route('/'),
            'create' => Pages\CreateRt::route('/create'),
            'edit' => Pages\EditRt::route('/{record}/edit'),
        ];
    }
}
