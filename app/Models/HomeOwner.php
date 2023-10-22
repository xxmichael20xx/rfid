<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class HomeOwner extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    /**
     * Attributes that can be filled
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'email',
        'contact_no',
        'gender',
        'profile',
        'metadata'
    ];

    /**
     * Attributes to change datatype
     */
    protected $casts = [
        'metadata' => 'json'
    ];

    /**
     * Attribute to be appended
     */
    protected $appends = [
        'full_name',
        'last_full_name',
        'profile_path',
        'grouped_block_lots',
        'age'
    ];

    /**
     * Initialize append methods
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }

    public function getLastFullNameAttribute()
    {
        return $this->middle_name . ', ' . $this->first_name . ' ' . $this->last_name;
    }

    public function getProfilePathAttribute()
    {
        return $this->profile;
    }

    public function getGroupedBlockLotsAttribute()
    {
        $groupedBlockLots = [];
        $blockLots = HomeOwnerBlockLot::where('home_owner_id', $this->id)->get();

        foreach ($blockLots as $blockLot) {
            $block = Block::find($blockLot->block);
            $lot = Lot::find($blockLot->lot);
            
            $blockName = $block->block;

            if (! isset($groupedBlockLots[$blockName])) {
                $groupedBlockLots[$blockName] = [];
            }
            $groupedBlockLots[$blockName][] = array_merge($blockLot->toArray(), [
                'lotName' => $lot->lot
            ]);
        }

        return $groupedBlockLots;
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }

        return null;
    }

    public function blockLots()
    {
        return $this->hasMany(HomeOwnerBlockLot::class, 'home_owner_id', 'id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'home_owner_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'home_owner_id', 'id');
    }

    public function rfidMonitorings()
    {
        return $this->hasMany(RfidMonitoring::class, 'home_owner_id', 'id');
    }

    public function vehicles()
    {
        return $this->hasMany(HomeOwnerVehicle::class, 'home_owner_id', 'id');
    }

    protected function profile(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                if (! $value) {
                    return '';
                }

                return asset('uploads/' . $value);
            }
        );
    }
}
