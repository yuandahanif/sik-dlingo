<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Penduduk;
use App\Models\Dusun;

class Rt extends Model
{
    use HasFactory;

    protected $table = 'rt';

    protected $fillable = ['nama', 'rt', 'dusun_id', 'kepala_id'];

    protected $casts = [
        'penduduk' => 'array',
    ];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'rt_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function kepala(): HasOne
    {
        return $this->hasOne(Penduduk::class, 'id', 'kepala_id');
    }
}
