<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use App\Models\Rt;
use App\Models\Dusun;
use App\Models\KartuKeluarga;
use App\Models\Asuransi;
use App\Models\Pertanahan;
use App\Models\RiwayatKependudukan;
use App\Models\PohonKeluarga;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';

    protected $fillable = ['nama', 'nik', 'rt_id', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'status_pernikahan', 'pekerjaan'];

    public function Kk(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function ketuaDukuh(): HasOne
    {
        return $this->hasOne(Dusun::class);
    }

    public function asuransi(): HasMany
    {
        return $this->hasMany(Asuransi::class);
    }

    public function tanah(): HasMany
    {
        return $this->hasMany(Pertanahan::class);
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function riwayat_kependudukan(): Hasone
    {
        return $this->hasOne(RiwayatKependudukan::class);
    }

    public function orang_tua_kandung(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, PohonKeluarga::class, 'parent_id', 'id');
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->tanggal_lahir)->age,
        );
    }
}
