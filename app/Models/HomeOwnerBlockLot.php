<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeOwnerBlockLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_owner_id',
        'block',
        'lot'
    ];

    public function block()
    {
        return $this->hasOne(Block::class, 'block', 'id');
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'lot', 'id');
    }
}
