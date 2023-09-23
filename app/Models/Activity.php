<?php

namespace App\Models;

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
}
