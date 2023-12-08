<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'browser',
        'ip',
        'time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
