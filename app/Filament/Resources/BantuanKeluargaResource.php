<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BantuanKeluargaResource\Pages;
use App\Filament\Resources\BantuanKeluargaResource\RelationManagers;
use App\Models\BantuanKeluarga;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BantuanKeluargaResource extends Resource
{
    protected static ?string $model = BantuanKeluarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bantuan';

    protected static ?string $slug = 'keluarga-bantuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kartu_keluarga_id')
                    ->relationship('keluarga', 'no_kk')
                    ->native(false)
                    ->label('No KK Penerima')
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
                TextColumn::make('keluarga.no_kk')->searchable()->label('No KK'),
                TextColumn::make('kategori.nama')->sortable()->label('Kategori Bantuan'),

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
            'index' => Pages\ListBantuanKeluargas::route('/'),
            'create' => Pages\CreateBantuanKeluarga::route('/create'),
            'edit' => Pages\EditBantuanKeluarga::route('/{record}/edit'),
        ];
    }
}
