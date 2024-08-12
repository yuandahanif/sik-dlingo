<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    use HasFactory;

    protected $table = 'dusun';

    protected $fillable = ['nama', 'kepala_id'];

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_id');
    }

    public function rt(): HasMany
    {
        return $this->hasMany(Rt::class);
    }
}
