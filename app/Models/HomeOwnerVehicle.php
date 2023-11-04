<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeOwnerVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'plate_number',
        'car_type',
        'car_name',
    ];

    public function rfid()
    {
        return $this->hasOne(Rfid::class, 'vehicle_id', 'id');
    }

    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
