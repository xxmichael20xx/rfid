<?php

namespace App\Http\Livewire\Rfid;

use App\Models\RfidMonitoring as RfidMonitoringModel;
use Livewire\Component;

class RfidMonitoring extends Component
{
    public $monitorings;

    public function mount()
    {
        $this->monitorings = RfidMonitoringModel::with(['rfidData', 'rfidData.vehicle.homeOwner'])->latest()->get();
    }

    public function render()
    {
        return view('livewire.Rfid.rfid-monitoring')
            ->extends('layouts.admin')
            ->section('content');
    }
}
