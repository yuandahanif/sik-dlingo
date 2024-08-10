<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKeluargaResource\Pages;
use App\Filament\Resources\KartuKeluargaResource\RelationManagers;
use App\Models\KartuKeluarga;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KartuKeluargaResource extends Resource
{
    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $navigationIcon = 'ri-file-paper-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('no_kk')
                    ->label('Nomer KK')
                    ->required(),
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
                TextColumn::make('status_ekonomi')->sortable()->label('Status Ekonomi')
                ->alignCenter(),
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
            'index' => Pages\ListKartuKeluargas::route('/'),
            'create' => Pages\CreateKartuKeluarga::route('/create'),
            'edit' => Pages\EditKartuKeluarga::route('/{record}/edit'),
        ];
    }
}
