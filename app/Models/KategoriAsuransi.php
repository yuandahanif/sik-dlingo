<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Asuransi;

class KategoriAsuransi extends Model
{
    use HasFactory;

    protected $table = 'asuransi_kategori';

    protected $fillable = ['nama', 'deskripsi'];

    public function asuransi(): BelongsTo
    {
        return $this->belongsTo(Asuransi::class, 'penduduk_id');
    }
}
