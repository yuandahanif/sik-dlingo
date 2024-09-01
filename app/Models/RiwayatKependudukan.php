<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKependudukan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kependudukan';

    protected $fillable = ['penduduk_id', 'tanggal_datang', 'tanggal_pindah'];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
}
