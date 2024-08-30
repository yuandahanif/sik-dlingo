<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PertanahanResource\Pages;
use App\Filament\Resources\PertanahanResource\RelationManagers;
use App\Models\Penduduk;
use App\Models\Pertanahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PertanahanResource extends Resource
{
    protected static ?string $model = Pertanahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'pertanahan';

    protected static ?string $navigationGroup = 'Pertananahan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->native(false)
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('nomor_sertifikat')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\Select::make('tipe_sertifikat')
                    ->options(Pertanahan::$tipe_sertifikat)
                    ->native(false)
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pemilik.nama')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_sertifikat')
                    ->searchable()->label('Nomer Sertifikat/Letter C'),
                Tables\Columns\TextColumn::make('tipe_sertifikat'),
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
                SelectFilter::make('pemilik')
                    ->options(Penduduk::has('tanah')->pluck('nama', 'id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('tipe_sertifikat')
                    ->label('Tipe Sertifikat')
                    ->multiple()
                    ->options(Pertanahan::$tipe_sertifikat)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePertanahans::route('/'),
        ];
    }
}
