<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pertanahan extends Model
{
    use HasFactory;

    protected $table = 'pertanahan';

    protected $fillable = ['penduduk_id', 'nomor_sertifikat', 'tipe_sertifikat'];

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
