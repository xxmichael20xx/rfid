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
        'block_lot_item'
    ];

    /**
     * Attribute that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
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
}
