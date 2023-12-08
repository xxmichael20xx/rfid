<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be fillable.
     */
    protected $fillable = [
        'home_owner_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'contact_no',
        'date_joined',
        'notes',
        'relation',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * The attributes that should be appended.
     */
    protected $appends = [
        'full_name',
        'last_full_name',
        'age'
    ];

    /**
     * Define relations
     */
    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }

    /**
     * Define appends attributes
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }

    public function getLastFullNameAttribute()
    {
        return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }

        return null;
    }
}
