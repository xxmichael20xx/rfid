<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentExpense extends Model
{
    use HasFactory;

    /**
     * Attributes that will be fillable
     */
    protected $fillable = [
        'type',
        'amount',
        'transaction_date',
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];
}
