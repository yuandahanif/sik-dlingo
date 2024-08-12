<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\KartuKeluarga;
use App\Models\KategoriBantuan;

class BantuanKeluarga extends Model
{
    use HasFactory;

    protected $table = 'bantuan_keluarga';

    protected $fillable = ['keterangan', 'kartu_keluarga_id', 'kategori_id'];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kartu_keluarga_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBantuan::class, 'kategori_id');
    }

}
