<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\KartuKeluargaPenduduk;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;

class ListPenduduks extends ListRecords implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PendudukResource::class;

    public ?array $data = [];

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Hidup' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'hidup')),
            'Meninggal' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'meninggal')),
        ];
    }

    protected function getAnggotaKeluarga(): Fieldset
    {
        $id = $this->data['kartu_keluarga_id'] ?? null;

        if (!$id) {
            return Fieldset::make('Anggota Keluarga yang sudah terdaftar');
        }

        $anggota_keluarga = KartuKeluarga::find($id)->anggota_keluarga()->get();

        $anggota_keluarga_placeholder_array = [];

        foreach ($anggota_keluarga as $anggota) {
            $anggota_keluarga_placeholder_array[] = Placeholder::make('anggota_keluarga' . $anggota->id)
                ->label($anggota->status_dalam_keluarga)
                ->content(fn(): string => $anggota->penduduk->nik . ' - ' . $anggota->penduduk->nama);
        }

        return
            Fieldset::make('Anggota Keluarga yang sudah terdaftar')
            ->columns(1)
            ->schema($anggota_keluarga_placeholder_array);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kk dan Penduduk')
                ->color('info')
                ->label('Tambah Anggota KK')
                ->steps([
                    Step::make('Kartu Keluarga')
                        ->afterValidation(function () {
                            if (!isset($this->data['kartu_keluarga_id']))
                                throw new Halt();
                        })
                        ->description('Pilih Kartu Keluarga')
                        ->schema([
                            Select::make('kartu_keluarga_id')
                                ->options(KartuKeluarga::class::all()->pluck('no_kk', 'id'))
                                ->native(false)
                                ->label('Nomer KK')
                                ->searchable()
                                ->live()
                                ->required()
                                ->afterStateUpdated(function (?string $state, ?string $old) {
                                    $this->data['kartu_keluarga_id'] = $state;
                                }),
                        ])
                        ->columns(1),
                    Step::make('Penduduk')
                        ->description('Tambah Penduduk')
                        ->schema([
                            $this->getAnggotaKeluarga(),
                            Select::make('status_dalam_keluarga')
                                ->label('Status Dalam Keluarga')
                                ->required()
                                ->options(KartuKeluargaPenduduk::$status_dalam_keluarga)
                                ->native(false),
                            ...PendudukResource::getCreateForm()
                        ]),
                ])->action(function (array $data) {
                    $kartu_keluarga = KartuKeluarga::find($data['kartu_keluarga_id']);
                    $penduduk = Penduduk::create($data);

                    $kartu_keluarga->anggota_keluarga()->create([
                        'penduduk_id' => $penduduk->id,
                        'status_dalam_keluarga' => $data['status_dalam_keluarga'],
                    ]);

                    Notification::make()
                        ->title('Berhasil ditambahkan')
                        ->success()
                        ->body('Anggota keluarga berhasil ditambahkan')
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function create(): void
    {
        try {
            dd($this->form->getState());
        } catch (Halt $exception) {
            return;
        }
    }
}
