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
use App\Models\KartuKeluargaPenduduk;
use App\Models\Asuransi;
use App\Models\Pertanahan;
use App\Models\RiwayatKependudukan;
use App\Models\PohonKeluarga;
use Str;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';

    protected $fillable = [
        'nama',
        'nik',
        'rt_id',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'status_pernikahan',
        'pekerjaan',
        'status_kependudukan',
        'status',
        'tanggal_meninggal'
    ];

    static $jenis_kelamin = [
        'perempuan' => 'Perempuan',
        'laki-laki' => 'Laki-Laki'
    ];

    static $agama = [
        'islam' => 'Islam',
        'katolik' => 'Katolik',
        'protestan' => 'Protestan',
        'konghucu' => 'Konghucu',
        'buddha' => 'Buddha',
        'hindu' => 'Hindu'
    ];

    static $status_kependudukan = [
        'pindah' => 'Pindah',
        'datang' => 'Datang',
        'null' => 'menetap'
    ];

    static $status_pernikahan = [
        'kawin' => 'Kawin',
        'belum kawin' => 'Belum Kawin',
        'cerai' => 'Cerai',
        'cerai mati' => 'Cerai Mati'
    ];

    static $status = [
        'hidup' => 'Hidup',
        'meninggal' => 'Meninggal'
    ];

    protected function tempatTanggalLahir(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->tempat_lahir . ', ' . Carbon::parse($this->tanggal_lahir)->locale('id')->format('d F Y'),
        );
    }

    public function kartu_keluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluargaPenduduk::class, "id", "penduduk_id",);
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
        return $this->hasManyThrough(Penduduk::class, PohonKeluarga::class, 'child_id', 'id');
    }

    public function bantuan(): HasMany
    {
        return $this->hasMany(BantuanPenduduk::class, 'penduduk_id');
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->tanggal_lahir)->age,
        );
    }
}
