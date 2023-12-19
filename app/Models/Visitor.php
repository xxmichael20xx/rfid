<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * Attributes that will be fillable
     */
    protected $fillable = [
        'home_owner_id',
        'first_name',
        'last_name',
        'token',
        'qr_image',
        'date_visited',
        'capture',
        'time_in',
        'time_out',
        'notes',
        'generated_at',
        'capture_in',
        'capture_out',
        'metadata'
    ];

    /**
     * Attribute that will be appended
     */
    protected $appends = [
        'full_name',
        'last_full_name',
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Model relationships
     */
    public function for()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id')->withTrashed();
    }

    /**
     * Appended attributes
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getLastFullNameAttribute()
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    protected function captureIn(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => asset('uploads/' . $value)
        );
    }

    protected function captureOut(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? asset('uploads/' . $value) : null
        );
    }
}
