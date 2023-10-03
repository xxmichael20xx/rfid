<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RfidMonitoring extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rfid',
        'date',
        'time_in',
        'time_out',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function rfidData()
    {
        return $this->belongsTo(Rfid::class, 'rfid', 'rfid');
    }
}
