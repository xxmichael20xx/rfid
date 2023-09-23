<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfid extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'home_owner_id',
        'rfid',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
