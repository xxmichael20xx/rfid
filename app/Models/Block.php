<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'block',
        'details',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    protected $appends = [
        'available_lots'
    ];

    /**
     * Define relationships
     */
    public function lots()
    {
        return $this->hasMany(Lot::class, 'block_id', 'id');
    }

    /**
     * Define append attributes
     */
    public function getAvailableLotsAttribute() {
        return Lot::where('block_id', $this->id)
            ->where('availability', 'available')
            ->get();
    }
}
