<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'home_owner_id',
        'type',
        'mode',
        'amount',
        'transaction_date',
        'reference',
        'status',
        'paid_on',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function biller()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
