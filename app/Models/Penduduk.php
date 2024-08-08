<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Penduduk extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'penduduk';


    public function Kk(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function Rt(): BelongsTo
    {
        return $this->belongsTo(RT::class);
    }

    public function KetuaDukuh(): HasOne
    {
        return $this->hasOne(Dusun::class);
    }

    protected function age(): Attribute
    {
        // dd(Carbon::create($this->tanggal_lahir)->diffInYears(now()));
        return Attribute::make(
            get: fn () => Carbon::parse($this->tanggal_lahir)->age,
        );
    }

    protected $fillable = ['nama', 'nik', 'rt_id', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'status_pernikahan', 'status_keluarga', 'pekerjaan'];
}
