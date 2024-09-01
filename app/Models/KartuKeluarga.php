<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use App\Models\Pertanahan;
use App\Models\KartuKeluargaPenduduk;
use App\Models\BantuanKeluarga;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KartuKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluarga';

    protected $fillable = ['no_kk', 'status_dtks'];

    protected $casts = [
        'anggota_keluarga' => 'array',
        'tanah_keluarga' => 'array',
    ];

    static $status_dtks = [
        'tidak terdaftar' => 'Tidak Terdaftar',
        'terdaftar' => 'Terdaftar'
    ];

    public function anggota_keluarga(): HasMany
    {
        return $this->hasMany(KartuKeluargaPenduduk::class, 'kartu_keluarga_id', 'id');
    }
    public function anggota_keluarga_belong(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'kartu_keluarga_penduduk', 'kartu_keluarga_id', 'penduduk_id');
    }

    public function tanah_keluarga(): HasManyThrough
    {
        return $this->hasManyThrough(Pertanahan::class, KartuKeluargaPenduduk::class, 'kartu_keluarga_id', 'penduduk_id');
    }

    public function bantuan(): HasMany
    {
        return $this->hasMany(BantuanKeluarga::class, 'kartu_keluarga_id');
    }
}
