<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDusun extends Model
{
    use HasFactory;

    protected $table = 'user_dusun';

    protected $fillable = ["user_id", 'dusun_id'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id', 'id');
    }

    public function dusun(): HasOne
    {
        return $this->hasOne(Dusun::class, 'id', 'dusun_id');
    }
}
