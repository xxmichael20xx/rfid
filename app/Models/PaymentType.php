<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes that can be filled
     */
    protected $fillable = [
        'type',
        'amount',
        'frequency',
        'is_recurring',
        'recurring_day',
        'metadata'
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];
}
