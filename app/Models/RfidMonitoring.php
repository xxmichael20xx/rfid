<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RfidMonitoring extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rfid',
        'date',
        'time_in',
        'time_out',
        'capture_in',
        'capture_out',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    protected function captureIn(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => asset('uploads/' . $value)
        );
    }

    protected function captureOut(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => asset('uploads/' . $value)
        );
    }

    public function rfidData()
    {
        return $this->belongsTo(Rfid::class, 'rfid', 'rfid');
    }
}