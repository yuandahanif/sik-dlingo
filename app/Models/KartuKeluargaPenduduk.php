<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluargaPenduduk extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluarga_penduduk';

    protected $fillable = ["penduduk_id", 'kartu_keluarga_id', 'status_dalam_keluarga'];

    static function status_dalam_keluarga(): array
    {
        return [
            'suami' => 'suami',
            'istri' => 'istri',
            'anak' => 'anak',
            'lainnya' => 'lainnya'
        ];
    }

    public function penduduk(): HasOne
    {
        return $this->hasOne(Penduduk::class);
    }

    public function kartu_keluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }
}
