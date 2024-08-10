<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBantuan extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluarga';

    protected $fillable = ['nama', 'deskripsi'];
}
