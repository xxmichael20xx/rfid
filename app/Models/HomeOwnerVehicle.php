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
        'car_type'
    ];
}
