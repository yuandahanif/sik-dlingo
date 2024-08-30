<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BantuanPendudukResource\Pages;
use App\Filament\Resources\BantuanPendudukResource\RelationManagers;
use App\Models\BantuanPenduduk;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BantuanPendudukResource extends Resource
{
    protected static ?string $model = BantuanPenduduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bantuan';

    protected static ?string $slug = 'penduduk-bantuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('penduduk_id')
                    ->relationship('penduduk', 'nama')
                    ->options(function () {
                        $kv = [];
                        $penduduk = Penduduk::get(['nik', 'nama', 'id']);
                        foreach ($penduduk as $key => $value) {
                            $kv[$value->id] = $value->nik . ' - ' . $value->nama;
                        }

                        return $kv;
                    })
                    ->native(false)
                    ->label('Nama Penerima')
                    ->searchable()
                    ->required()
                    ->preload(),
                Select::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->native(false)
                    ->label('Kategori Bantuan')
                    ->searchable()
                    ->required()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penduduk.nama')->searchable()->label('Penerima Bantuan'),
                TextColumn::make('kategori.nama')->sortable()->label('Kategori Bantuan'),
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
                    ->options(Penduduk::has('bantuan')->pluck('nama', 'id'))
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
            'index' => Pages\ListBantuanPenduduks::route('/'),
            'create' => Pages\CreateBantuanPenduduk::route('/create'),
            'edit' => Pages\EditBantuanPenduduk::route('/{record}/edit'),
        ];
    }
}
