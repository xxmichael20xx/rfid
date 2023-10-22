<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfid extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'rfid',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function vehicle()
    {
        return $this->belongsTo(HomeOwnerVehicle::class, 'vehicle_id', 'id');
    }
}
