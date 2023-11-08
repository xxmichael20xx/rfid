<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes that will be fillable
     */
    protected $fillable = [
        'home_owner_id',
        'type_id',
        'mode',
        'amount',
        'transaction_date',
        'date_paid',
        'due_date',
        'reference',
        'is_recurring',
        'recurring_date',
        'status',
        'metadata',
    ];

    /**
     * Attribute that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Define model relationships
     */
    public function biller()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id')->withTrashed();
    }

    public function paymentType()
    {
        return $this->hasOne(PaymentType::class, 'id', 'type_id')->withTrashed();
    }
}
