<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Penduduk;
use App\Models\KategoriBantuan;

class BantuanPenduduk extends Model
{
    use HasFactory;

    protected $table = 'bantuan_penduduk';

    protected $fillable = ['keterangan', 'penduduk_id', 'kategori_id'];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBantuan::class, 'kategori_id');
    }
}
