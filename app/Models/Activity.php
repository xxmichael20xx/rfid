<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Attributes that can be filled
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'start_date',
        'end_date',
        'metadata'
    ];

    /**
     * Attributes to change datatype
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Attributes to be appended
     */
    protected $appends = [
        'event_date'
    ];

    public function getEventDateAttribute()
    {
        if ($this->start_date === $this->end_date) {
            return Carbon::parse($this->start_date)->format('M d, Y');
        } else {
            return Carbon::parse($this->start_date)->format('M d') . ' - '. Carbon::parse($this->end_date)->format('M d, Y');
        }
    }
}
