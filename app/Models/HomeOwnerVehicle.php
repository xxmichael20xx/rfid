<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeOwnerVehicle extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id')->withTrashed();
    }
}
