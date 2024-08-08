<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        return $this->hasOne(Dukuh::class);
    }

    protected $fillable = ['nama', 'nik', 'rw_id', 'rt_id', 'gender', 'tmp_lahir', 'tgl_lahir', 'agama', 'alamat', 'status_pernikahan', 'status_keluarga', 'pekerjaan'];
}
