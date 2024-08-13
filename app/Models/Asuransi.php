<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Penduduk;
use App\Models\KategoriAsuransi;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asuransi extends Model
{
    use HasFactory;

    protected $table = 'asuransi';

    protected $fillable = ['keterangan', 'nomor_asuransi', 'penduduk_id', 'kategori_id'];

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAsuransi::class);
    }
}
