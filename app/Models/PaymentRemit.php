<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRemit extends Model
{
    use HasFactory;

    /**
     * Attributes that will be fillable
     */
    protected $fillable = [
        'title',
        'amount',
        'date_remitted'
    ];
}
