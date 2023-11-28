<?php

namespace App\Http\Livewire\Rfid;

use App\Models\HomeOwner;
use App\Models\HomeOwnerVehicle;
use App\Models\Rfid;
use App\Models\RfidMonitoring as RfidMonitoringModel;
use Livewire\Component;

class RfidMonitoring extends Component
{
    public $monitorings;

    public function mount()
    {
        $this->monitorings = collect(RfidMonitoringModel::latest()->get())->map(function($item) {
            // get the rfid data
            $rfidData = Rfid::withTrashed()->where('rfid', $item->rfid)->first();

            // get the vehicle data
            $vehicle = HomeOwnerVehicle::withTrashed()->where('id', $rfidData->vehicle_id)->first();

            // get the homeOwner data
            $homeOwner = HomeOwner::withTrashed()->where('id', $vehicle->home_owner_id)->first();

            return [
                'rfid' => $item->rfid,
                'date' => $item->date,
                'time_in' => $item->time_in,
                'time_out' => $item->time_out,
                'capture_in' => $item->capture_in,
                'capture_out' => $item->capture_out,
                'home_owner' => $homeOwner->last_full_name
            ];
        });
    }

    public function render()
    {
        return view('livewire.Rfid.rfid-monitoring')
            ->extends('layouts.admin')
            ->section('content');
    }
}
