<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DukuhResource\Pages;
use App\Filament\Resources\DukuhResource\RelationManagers;
use App\Models\Dusun;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class DusunResource extends Resource
{
    protected static ?string $model = Dusun::class;

    protected static ?string $navigationIcon = 'gameicon-village';

    protected static ?string $navigationLabel = 'Dusun';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Dusun')
                    ->required()
                    ->maxLength(255),
                Select::make('ketua_id')
                    ->relationship('ketua', 'nama')
                    ->label('Ketua Dusun')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('ketua_id')->label('Ketua Dusun'),
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
            'index' => Pages\ListDusun::route('/'),
            'create' => Pages\CreateDusun::route('/create'),
            'edit' => Pages\EditDusun::route('/{record}/edit'),
        ];
    }
}
