<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DusunResource\Pages;
use App\Filament\Resources\DusunResource\RelationManagers;
use App\Models\Dusun;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->relationship('kepala', 'nama')
                    ->label('Ketua Dusun')
                    ->native(false)
                    ->searchable()
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
                TextColumn::make('kepala.nama')->label('Ketua Dusun'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make('detail')
                    ->fillForm(fn(Dusun $record): array => [
                        'nama' => $record->nama,
                        'kepala_id' => $record->kepala_id,
                    ])
                    ->form([
                        TextInput::make('nama')
                            ->label('Nama Dusun'),
                        Select::make('kepala_id')
                            ->label('Ketua Dusun')
                            ->relationship('kepala', 'nama')
                            ->native(false),
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
            'index' => Pages\ListDusun::route('/'),
            'create' => Pages\CreateDusun::route('/create'),
            'edit' => Pages\EditDusun::route('/{record}/edit'),
        ];
    }
}
