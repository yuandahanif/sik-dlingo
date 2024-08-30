<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DusunResource\Pages;
use App\Filament\Resources\DusunResource\RelationManagers;
use App\Models\Dusun;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;


class DusunResource extends Resource
{
    protected static ?string $model = Dusun::class;

    protected static ?string $navigationIcon = 'gameicon-village';

    protected static ?string $navigationLabel = 'Dusun';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'dusun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Dusun')
                    ->required()
                    ->maxLength(255),
                Select::make('kepala_id')
                    ->native(false)
                    ->relationship(name: 'kepala', titleAttribute: 'nama')
                    ->label('Ketua Dusun')
                    ->searchable()
                    ->options(function () {
                        $kv = [];
                        $penduduk = Penduduk::get(['nik', 'nama', 'id']);
                        foreach ($penduduk as $key => $value) {
                            $kv[$value->id] = $value->nik . ' - ' . $value->nama;
                        }

                        return $kv;
                    })
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('rt_count')->counts('rt')
                    ->label('Jumlah RT')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('penduduk_count')->counts('penduduk')
                    ->label('Jumlah Penduduk')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('kepala.nama')->label('Ketua Dusun'),
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
            'index' => Pages\ListDusuns::route('/'),
            'create' => Pages\CreateDusun::route('/create'),
            'edit' => Pages\EditDusun::route('/{record}/edit'),
        ];
    }
}
