<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dukuh extends Model
{
    use HasFactory;

    protected $table = 'dukuh';

    protected $fillable = ['nama', 'ketua_id'];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'ketua_id');
    }

    public function rt(): HasMany
    {
        return $this->hasMany(Rt::class);
    }
}
