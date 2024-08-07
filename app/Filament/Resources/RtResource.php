<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RtResource\Pages;
use App\Filament\Resources\RtResource\RelationManagers;
use App\Models\Rt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RtResource extends Resource
{
    protected static ?string $model = Rt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama RT')
                    ->required(),
                Forms\Components\TextInput::make('rt')
                    ->numeric()
                    ->label('Nomor RT')
                    ->required(),
                Forms\Components\Select::make('rw_id')
                    ->relationship(name: 'rw', titleAttribute: 'nama')
                    ->required(),
            ])
            ->statePath('create_rt')
            ->model($form->getModel());
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
            'index' => Pages\ListRts::route('/'),
            'create' => Pages\CreateRt::route('/create'),
            'edit' => Pages\EditRt::route('/{record}/edit'),
        ];
    }
}
