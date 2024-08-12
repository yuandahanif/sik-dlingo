<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BantuanKeluargaResource\Pages;
use App\Filament\Resources\BantuanKeluargaResource\RelationManagers;
use App\Models\BantuanKeluarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BantuanKeluargaResource extends Resource
{
    protected static ?string $model = BantuanKeluarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bantuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
