<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PohonKeluarga extends Model
{
    use HasFactory;

    protected $table = 'pohon_keluarga';

    protected $fillable = [ 'parent_id', 'child_id', 'hubungan'];

    static $hubungan = [
        'ayah' => 'Ayah',
        'ibu' => 'Ibu'
    ];

    // public function kartu_keluarga()
    // {
    //     return $this->belongsTo(KartuKeluarga::class, 'kk_id');
    // }

    public function parent()
    {
        return $this->belongsTo(Penduduk::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Penduduk::class, 'child_id');
    }
}
