<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class KartuKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluarga';

    protected $fillable = ['nomor_kk', 'alamat', 'status_keluarga', 'status_dalam_keluarga'];

    public function anggota_keluarga(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function tanah_keluarga(): HasManyThrough
    {
        return $this->hasManyThrough(Pertanahan::class, Penduduk::class, 'kartu_keluarga_id', 'penduduk_id');
    }
}
