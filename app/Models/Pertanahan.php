<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pertanahan extends Model
{
    use HasFactory;

    protected $table = 'pertanahan';

    protected $fillable = ['penduduk_id', 'nomor_sertifikat', 'tipe_sertifikat'];

    static $tipe_sertifikat = [
        'surat hak milik' => 'Surat Hak Milik',
        'letter c' => 'Letter C'

    ];

    protected function tipe_sertifikat(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
