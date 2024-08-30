<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsuransiResource\Pages;
use App\Filament\Resources\AsuransiResource\RelationManagers;
use App\Models\Asuransi;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsuransiResource extends Resource
{
    protected static ?string $model = Asuransi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Asuransi';

    protected static ?string $slug = 'asuransi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori_id')
                    ->relationship(name: 'kategori', titleAttribute: 'nama')
                    ->label('kategori')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('penduduk_id')
                    ->relationship(name: 'pemilik', titleAttribute: 'nama')
                    ->options(function () {
                        $kv = [];
                        $penduduk = Penduduk::get(['nik', 'nama', 'id']);
                        foreach ($penduduk as $key => $value) {
                            $kv[$value->id] = $value->nik . ' - ' . $value->nama;
                        }

                        return $kv;
                    })
                    ->label('Pemilik')
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nomor_asuransi')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_asuransi')
                    ->label('Nomor Asuransi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pemilik.nama')
                    ->label('Pemilik')
                    ->searchable()
                    ->sortable(),
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
                SelectFilter::make('penduduk_id')
                    ->options(Penduduk::has('asuransi')->pluck('nama', 'id'))
                    ->multiple()
                    ->preload()
                    ->label('Pemilik')
                    ->native(false)
                    ->columnSpan(1),
                SelectFilter::make('kategori_id')->relationship('kategori', 'nama')->multiple()->preload()->label('Kategori')->native(false)->columnSpan(1),

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
            'index' => Pages\ListAsuransis::route('/'),
            'create' => Pages\CreateAsuransi::route('/create'),
            'edit' => Pages\EditAsuransi::route('/{record}/edit'),
        ];
    }
}
