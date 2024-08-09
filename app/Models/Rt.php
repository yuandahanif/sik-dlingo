<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rt extends Model
{
    use HasFactory;

    protected $table = 'rt';

    protected $fillable = ['nama', 'rt', 'rw_id', 'dukuh_id', 'ketua_id'];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }
}
