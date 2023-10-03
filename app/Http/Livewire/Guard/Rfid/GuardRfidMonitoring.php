<?php

namespace App\Http\Livewire\Guard\Rfid;

use App\Models\Rfid;
use App\Models\RfidMonitoring;
use Carbon\Carbon;
use Livewire\Component;

class GuardRfidMonitoring extends Component
{
    protected $listeners = [
        'logEntry',
        'validateEntry',
    ];

    public $monitorings;
    public $homeOwner;

    public function logEntry()
    {
        $id = $this->homeOwner->rfid->rfid;
        $today = Carbon::now();
        $currentDate = $today->format('m/d/Y');
        $currentTime = $today->format('h:i A');
        $monitoring = RfidMonitoring::where('rfid', $id)
            ->where('date', $currentDate)
            ->where('time_out', '=', 'N/A')
            ->first();

        if (! $monitoring) {
            $newMonitoring = RfidMonitoring::create([
                'rfid' => $id,
                'date' => $currentDate,
                'time_in' => $currentTime
            ]);

            if ($newMonitoring) {
                $this->emit('new-entry', [
                    'date' => $currentDate,
                    'time' => $currentTime
                ]);
            }
        } else {
            $monitoring->update([
                'time_out' => $currentTime
            ]);

            $this->emit('updated-entry', [
                'date' => $currentDate,
                'time' => $currentTime
            ]);
        }

        $this->fetchLatest();
    }

    /**
     * Check if the scanned rfid exists
     */
    public function validateEntry($id)
    {
        $rfidExists = Rfid::with('homeOwner', 'homeOwner.rfid')->where('rfid', $id)->first();

        if (! $rfidExists) {
            $this->emit('invalid-rfid');
            return false;
        }

        $this->homeOwner = $rfidExists->homeOwner;
        $this->emit('homeowner-data');
    }

    protected function fetchLatest()
    {
        $this->monitorings = RfidMonitoring::with(['rfidData', 'rfidData.homeOwner'])->latest()->get();
    }

    public function mount()
    {
        $this->fetchLatest();
    }

    public function render()
    {
        return view('livewire.guard.rfid.guard-rfid-monitoring')
            ->extends('layouts.guard')
            ->section('content');
    }
}
