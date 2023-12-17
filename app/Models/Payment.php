<?php

namespace App\Models;

use Carbon\Carbon;
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
        'block_lot',
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
        'block_lot_item',
        'received_by',
    ];

    /**
     * Attribute that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    protected $appends = [
        'payment_received_by',
        'amount_f',
        'transaction_date_f',
    ];

    public function getBlockLotItemAttribute()
    {
        if (! $this->block_lot) {
            return 'No lot selected.';
        }

        $blockLot = HomeOwnerBlockLot::find($this->block_lot);
        $blockId = $blockLot->block;
        $lotId = $blockLot->lot;

        $block = Block::withTrashed()->where('id', $blockId)->first();
        $lot = Lot::withTrashed()->where('id', $lotId)->first();

        return sprintf('Block %s - Lot %s', $block->block, $lot->lot);
    }

    public function getPaymentReceivedByAttribute()
    {
        if ($this->received_by == null) {
            return 'N/A';
        }

        return User::find($this->received_by)->full_name;
    }

    public function getAmountFAttribute()
    {
        return '₱' . number_format($this->amount, 2);
    }

    public function getTransactiondateFAttribute()
    {
        if (! $this->transaction_date) {
            return 'N/A';
        }

        return Carbon::parse($this->transaction_date)->format('M d, Y');
    }

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

    public function blockLot()
    {
        return $this->belongsTo(HomeOwnerBlockLot::class, 'block_lot', 'id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
