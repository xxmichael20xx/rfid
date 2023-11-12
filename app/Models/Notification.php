<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Attributes that can be fillable
     */
    protected $fillable = [
        'home_owner_id',
        'title',
        'content',
        'is_read'
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Attributes that will be appended
     */
    protected $appends = [
        'formatted_date'
    ];

    /**
     * Attribute methods
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Table relationships
     */
    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
