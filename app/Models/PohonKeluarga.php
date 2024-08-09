<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PohonKeluarga extends Model
{
    use HasFactory;

    protected $table = 'pohon_keluarga';

    protected $fillable = ['kk_id', 'parent_id', 'child_id', 'hubungan'];

    public function kartu_keluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id');
    }
}
