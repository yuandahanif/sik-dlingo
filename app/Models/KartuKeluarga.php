<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use App\Models\Pertanahan;
use App\Models\KartuKeluargaPenduduk;

class KartuKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluarga';

    protected $fillable = ['no_kk', 'status_ekonomi'];

    protected $casts = [
        // 'anggota_keluarga' => 'array',
        'tanah_keluarga' => 'array',
    ];

    public function anggota_keluarga(): HasMany
    {
        return $this->hasMany(KartuKeluargaPenduduk::class, 'kartu_keluarga_id', 'id');
    }

    public function tanah_keluarga(): HasManyThrough
    {
        return $this->hasManyThrough(Pertanahan::class, KartuKeluargaPenduduk::class, 'kartu_keluarga_id', 'penduduk_id');
    }
}
