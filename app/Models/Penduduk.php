<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penduduk extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'penduduk';


    public function KK(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function RW(): BelongsTo
    {
        return $this->belongsTo(RW::class);
    }

    public function RT(): BelongsTo
    {
        return $this->belongsTo(RT::class);
    }

    protected $fillable = ['nama', 'nik', 'rw_id', 'rt_id', 'gender', 'tmp_lahir', 'tgl_lahir', 'agama', 'alamat', 'status_pernikahan', 'status_keluarga', 'pekerjaan'];
}
