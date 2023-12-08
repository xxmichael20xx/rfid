<?php

namespace App\Http\Livewire\Rfid;

use App\Models\HomeOwner;
use App\Models\HomeOwnerVehicle;
use App\Models\Rfid;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RfidList extends Component
{
    public $rfids;
    public $unassignedVehicles;

    protected $listeners = [
        'deleteRfid',
        'setRfid',
        'selectVehicle',
        'unselectVehicle'
    ];

    public $rfidForm = [
        'vehicle_id' => '',
        'rfid' => ''
    ];

    public function preSubmit()
    {
        // validate the rfid form
        $this->validate([
            'rfidForm.vehicle_id' => ['required'],
            'rfidForm.rfid' => ['required', Rule::unique('rfids', 'rfid')]
        ]);

        $this->emit('pre.submit-confirmation', $this->rfidForm['rfid']);
    }

    public function create()
    {
        // assign the rfid to a home owner
        Rfid::create($this->rfidForm);

        // emit the success dialog
        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Assign Success',
            'message' => 'RFID has been successfully assigned to a home owner!',
            'reload' => true
        ]);
    }

    public function deleteRfid($id)
    {
        $rfid = Rfid::find($id)->first();

        if (! $rfid) {
            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'RFID data not found!',
            ]);
        } else {
            $rfid->delete();

            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Delete success',
                'message' => 'RFID has been deleted!',
                'reload' => true
            ]);
        }
    }

    public function setRfid($id)
    {
        $this->rfidForm['rfid'] = $id;
    }

    public function selectVehicle($id)
    {
        $this->rfidForm['vehicle_id'] = $id;
    }

    /**
     * Initialize component data
     */
    public function mount()
    {
        // get all vehicles
        $vehicles = HomeOwnerVehicle::with(['rfid'])->get();
        $firstVehicle = '';
        $this->unassignedVehicles = [];

        foreach ($vehicles as $vehicle) {
            $homeOwner = HomeOwner::where('id', $vehicle->home_owner_id)->first();

            if (! $homeOwner) {
                continue;
            }

            $homeOwner = $vehicle->homeOwner->last_full_name;
            $available = [
                'vehicle_id' => $vehicle->id,
                'vehicle' => $vehicle->car_type . ' - ' . $vehicle->plate_number
            ];

            if (! $vehicle->rfid) {
                if (! isset($this->unassignedVehicles[$homeOwner])) {
                    $this->unassignedVehicles[$homeOwner] = [];
                }
                $this->unassignedVehicles[$homeOwner][] = $available;

                if (! $firstVehicle) {
                    $firstVehicle = $vehicle->id;
                }
            }
        }

        $this->rfidForm['vehicle_id'] = $firstVehicle;

        // get the list of Rfids
        $this->rfids = Rfid::with(['vehicle'])->get();
    }

    public function render()
    {
        return view('livewire.Rfid.rfid-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
