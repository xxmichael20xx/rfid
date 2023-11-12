<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Attributes that can be fillable
     */
    protected $fillable = [
        'home_owner_id',
        'title',
        'content',
        'is_read'
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Table relationships
     */
    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
