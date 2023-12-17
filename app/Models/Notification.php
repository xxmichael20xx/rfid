<?php

namespace App\Models;

use Carbon\Carbon;
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
        'is_read',
        'sent_by',
        'type',
        'is_visitor_request',
        'visitor_request_status',
        'visitor_request_denied_reason',
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Attributes that will be appended
     */
    protected $appends = [
        'formatted_date',
        'sender_name',
    ];

    /**
     * Attribute methods
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getSenderNameAttribute()
    {
        return User::find($this->sent_by)->full_name;
    }

    /**
     * Table relationships
     */
    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Notification $model) {
            $model->sent_by = auth()->user()->id;
            $model->type = match ($model->title) {
                'New Activity' => 'Activity',
                'Visitor Request' => 'Visitor Request',
                'Visitor Entry' => 'Visitor Entry',
                'Payment Reminder' => 'Payment Reminder',
                'default' => 'others'
            };
        });
    }
}
