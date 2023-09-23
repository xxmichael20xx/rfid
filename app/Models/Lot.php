<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'block_id',
        'lot',
        'details',
        'availability',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id', 'id');
    }
}
