<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RtResource\Pages;
use App\Filament\Resources\RtResource\RelationManagers;
use App\Models\Rt;
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

class RtResource extends Resource
{
    protected static ?string $model = Rt::class;

    protected static ?string $navigationLabel = 'RT';

    protected static ?string $navigationIcon = 'healthicons-f-village';

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
                    ->relationship(name: 'dusun', titleAttribute: 'nama', ignoreRecord: true)
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->preload(),
                Select::make('kepala_id')
                    ->relationship(name: 'penduduk', titleAttribute: 'nama', ignoreRecord: true)
                    ->required()->label('Kepala RT')
                    ->native(false)
                    ->searchable()
                    ->preload(),
            ])
            ->statePath('data')
            ->model($form->getModel())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama RT'),
                TextColumn::make('dusun.nama')
                    ->label('Dusun'),
                TextColumn::make('kepala.nama')
                    ->label('Kepala RT'),
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
