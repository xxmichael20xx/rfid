<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringPayment extends Model
{
    use HasFactory;

    /**
     * Attribute that will be fillable
     */
    protected $fillable = [
        'payment_type_id',
        'recurring_day'
    ];
}
