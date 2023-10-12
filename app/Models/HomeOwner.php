<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeOwner extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Attributes that can be filled
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'block',
        'lot',
        'contact_no',
        'profile',
        'metadata'
    ];

    /**
     * Attributes to change datatype
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Attribute to be appended
     */
    protected $appends = [
        'full_name',
        'profile_path'
    ];

    /**
     * Initialize append methods
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }

    public function getProfilePathAttribute()
    {
        return $this->profile;
    }

    public function myBlock()
    {
        return $this->hasOne(Block::class, 'id', 'block');
    }

    public function myLot()
    {
        return $this->hasOne(Lot::class, 'id', 'lot');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'home_owner_id', 'id');
    }

    public function rfid()
    {
        return $this->hasOne(Rfid::class, 'home_owner_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'home_owner_id', 'id');
    }

    public function rfidMonitorings()
    {
        return $this->hasMany(RfidMonitoring::class, 'home_owner_id', 'id');
    }

    protected function profile(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                if (! $value) {
                    return '';
                }

                return asset('uploads/' . $value);
            }
        );
    }
}
